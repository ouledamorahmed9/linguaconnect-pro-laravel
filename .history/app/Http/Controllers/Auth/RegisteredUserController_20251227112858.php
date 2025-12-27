<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudySubject;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Fetch active subjects for the dropdown
        $studySubjects = StudySubject::active()->ordered()->get();
        
        return view('auth.register', compact('studySubjects'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'study_subject_id' => ['required', 'exists:study_subjects,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->study_subject_id = $request->study_subject_id;
        
        // --- HARDCODED SECURITY ---
        // Any user registering via this form is AUTOMATICALLY a client.
        $user->role = 'client'; 

        // =========================================================
        // START REFERRAL LOGIC
        // =========================================================
        $referrer = null; // We save the referrer object to use it AFTER saving the user

        // Check if the user came from a referral link (cookie)
        if ($request->hasCookie('referral_code')) {
            $code = $request->cookie('referral_code');
            
            // Find the coordinator who owns this code
            $foundReferrer = User::where('referral_code', $code)->first();

            // Link them ONLY if the referrer exists and is a coordinator
            if ($foundReferrer && $foundReferrer->role === 'coordinator') {
                $user->referrer_id = $foundReferrer->id;
                $referrer = $foundReferrer; // Keep this for step 2
            }
        }
        // =========================================================
        // END REFERRAL LOGIC
        // =========================================================

        $user->save(); // <--- User gets an ID here

        // =========================================================
        // STEP 2: AUTO-ASSIGN MANAGEMENT
        // =========================================================
        // Now that the user is saved, we attach them to the coordinator's managed list
        if ($referrer) {
            try {
                // This ensures the student appears in the "My Clients" list immediately
                $referrer->managedClients()->attach($user->id);
            } catch (\Exception $e) {
                // If the relationship is already set or fails, we fail silently 
                // so we don't block the registration.
            }
        }
        // =========================================================

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('client.dashboard');
    }
}