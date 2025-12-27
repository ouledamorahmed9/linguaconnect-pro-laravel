<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudySubject; // <--- Import this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    /**
     * Display a listing of all clients.
     */
    public function index(): View
    {
        $clients = User::where('role', 'client')->latest()->get();
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View
    {
        // Fetch active subjects for the dropdown
        $studySubjects = StudySubject::active()->ordered()->get();

        return view('admin.clients.create', compact('studySubjects'));
    }

    /**
     * Store a newly created client in the database.
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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'study_subject_id' => $request->study_subject_id,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);

        return redirect()->route('admin.clients.index')->with('status', 'Client created successfully!');
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(User $client): View
    {
        // Fetch the client's current active subscription
        $activeSubscription = $client->subscriptions()->where('status', 'active')->latest('starts_at')->first();
        
        // Fetch subjects for the dropdown
        $studySubjects = StudySubject::active()->ordered()->get();

        return view('admin.clients.edit', [
            'client' => $client,
            'subscription' => $activeSubscription,
            'studySubjects' => $studySubjects, // <--- Pass this to view
        ]);
    }

    /**
     * Update the specified client in the database.
     */
    public function update(Request $request, User $client): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($client->id)],
            'phone' => ['required', 'string', 'max:20'], // <--- Validate Phone
            'study_subject_id' => ['required', 'exists:study_subjects,id'], // <--- Validate Subject
        ]);

        // Update basic info
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'study_subject_id' => $request->study_subject_id,
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $client->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.clients.index')->with('status', 'Client profile updated successfully!');
    }

    /**
     * Remove the specified client from the database.
     */
    public function destroy(User $client): RedirectResponse
    {
        if (!$client->hasRole('client')) {
            return back()->with('error', 'Invalid user specified.');
        }

        $client->delete();

        return redirect()->route('admin.clients.index')->with('status', 'Client deleted successfully!');
    }
}