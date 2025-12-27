<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudySubject;
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'. User::class],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]+$/'],
            'study_subject_id' => ['required', 'exists:study_subjects,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

$user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    
    // Assign role safely (assuming form sends 'student' or 'teacher')
    $user->role = $request->role; 

    $user->save();

    event(new Registered($user));
    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
}}