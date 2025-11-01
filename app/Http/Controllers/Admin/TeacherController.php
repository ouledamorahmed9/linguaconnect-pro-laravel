<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TeacherController extends Controller
{
    /**
     * Display a listing of all teachers.
     */
    public function index(): View
    {
        $teachers = User::where('role', 'teacher')->latest()->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create(): View
    {
        return view('admin.teachers.create');
    }

    /**
     * Store a newly created teacher in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher created successfully!');
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(User $teacher): View
    {
        // THIS IS THE NEW LOGIC
        // Fetch all clients to display in the checklist
        $allClients = User::where('role', 'client')->orderBy('name')->get();
        
        // Get a simple array of IDs for the clients THIS teacher is already assigned to
        $assignedClientIds = $teacher->clients->pluck('id')->toArray();

        return view('admin.teachers.edit', compact('teacher', 'allClients', 'assignedClientIds'));
    }

    /**
     * Update the specified teacher in the database.
     */
    public function update(Request $request, User $teacher): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($teacher->id)],
        ]);

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $teacher->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher profile updated successfully!');
    }

    /**
     * Remove the specified teacher from the database.
     */
    public function destroy(User $teacher): RedirectResponse
    {
        if ($teacher->hasRole('admin')) {
            return back()->with('error', 'Cannot delete an administrator.');
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher deleted successfully!');
    }
}
