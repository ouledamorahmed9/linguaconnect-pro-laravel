<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // --- ** هذا هو السطر الذي يقوم بالإصلاح ** ---
        // 1. نقوم بتعريف المتغير $user أولاً
        $user = $request->user();
        // --- ** نهاية الإصلاح ** ---

        // 2. الآن نستخدم المتغير $user في كل مكان
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        
        // --- 3. إضافة منطق الصورة الجديد (سيعمل الآن) ---

// Handle profile photo upload
if ($request->hasFile('photo')) {
    if ($user->profile_photo_path) {
        Storage::disk('public')->delete($user->profile_photo_path);
    }
    $user->profile_photo_path = $request->file('photo')->store('profile-photos', 'public');
}

// Handle profile photo deletion
if ($request->boolean('delete_photo')) {
    if ($user->profile_photo_path) {
        Storage::disk('public')->delete($user->profile_photo_path);
    }
    $user->profile_photo_path = null;
}

// Handle Banner Upload
if ($request->hasFile('banner_photo')) {
    if ($user->banner_photo_path) {
        Storage::disk('public')->delete($user->banner_photo_path);
    }
    $user->banner_photo_path = $request->file('banner_photo')->store('banners', 'public');
}

// Handle Banner Deletion
if ($request->boolean('delete_banner')) {
    if ($user->banner_photo_path) {
        Storage::disk('public')->delete($user->banner_photo_path);
    }
    $user->banner_photo_path = null;
}

        // --- نهاية منطق الصورة ** ---

        // 4. حفظ كل التغييرات في $user
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();
        // حذف الصورة الشخصية للمستخدم عند حذف الحساب
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
    * Generate a new API token for the teacher.
    */
    public function generateToken(Request $request)
    {
        $user = $request->user();

        // Revoke all old tokens to ensure only one is active
        $user->tokens()->delete();

        // Create a new token
        $token = $user->createToken('chrome-extension-token');

        // Return to the profile page with the new token
        // We flash to session so it's shown only ONCE
        return redirect()->route('profile.edit')
            ->with('status', 'token-generated')
            ->with('token', $token->plainTextToken);
    }
}