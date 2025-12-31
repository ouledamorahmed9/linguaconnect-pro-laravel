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
use Carbon\Carbon; 

class TeacherController extends Controller
{
    /**
     * Display a listing of all teachers.
     */
    public function index()
    {
        $teachers = User::where('role', 'teacher')
                        ->withCount('clients') 
                        ->orderBy('name')
                        ->paginate(15); 

        return view('admin.teachers.index', [
            'teachers' => $teachers,
        ]);
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
            'subject' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'subject' => $request->subject,
        ]);

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher created successfully!');
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(User $teacher): View
    {
        // --- MODIFICATION: Fetch only clients with ACTIVE subscriptions ---
        $clients = User::where('role', 'client')
            ->whereHas('subscriptions', function($query) {
                $query->where('status', 'active')
                      ->where('end_date', '>', Carbon::now());
            })
            ->get();
        // ----------------------------------------------------------------

        return view('admin.teachers.edit', [
            'teacher' => $teacher,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified teacher in the database.
     */
    public function update(Request $request, User $teacher): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($teacher->id)],
            'subject' => ['nullable', 'string', 'max:255'], 
            'clients' => ['array', 'nullable'], // Validating the array
            'clients.*' => ['exists:users,id'],
        ]);

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject, 
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $teacher->update(['password' => Hash::make($request->password)]);
        }

        // --- MODIFICATION: Link/Unlink Clients ---
        if ($request->has('clients')) {
            $teacher->clients()->sync($request->clients);
        } else {
            $teacher->clients()->detach();
        }
        // -----------------------------------------

        return redirect()->route('admin.teachers.edit', $teacher)->with('status', 'تم تحديث ملف المعلم بنجاح.');
    }

    /**
     * Remove the specified teacher from the database.
     */
    public function destroy(User $teacher): RedirectResponse
    {
        if ($teacher->hasRole('admin')) {
            return back()->with('error', 'Cannot delete an administrator.');
        }

        $teacher->clients()->detach(); // Clean up relationships
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher deleted successfully!');
    }

public function toggle(Request $request, User $teacher)
    {
        // 1. Validate the incoming request
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:users,id',
            'checked' => 'required|boolean',
        ]);

        $client = User::find($validated['client_id']);

        // 2. Security Check: Is the user a client?
        if (!$client || !$client->hasRole('client')) {
            return response()->json(['status' => 'error', 'message' => 'Invalid client.'], 404);
        }

        // 3. Business Logic Check: Is the client eligible?
        // REMOVED: the strict lessons_used check to match the list logic
        $isEligible = $client->subscriptions()
                            ->where('status', 'active')
                            ->where('ends_at', '>', now())
                            ->exists();

        // 4. Perform the action
        if ($validated['checked']) {
            if (!$isEligible) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Client subscription is not active. Cannot assign.'
                ], 422);
            }
            
            // Link the student to the teacher
            $teacher->clients()->syncWithoutDetaching([$client->id]);
            return response()->json(['status' => 'attached', 'message' => 'Client assigned.']);

        } else {
            // Unlink the student
            $teacher->clients()->detach($client->id);
            return response()->json(['status' => 'detached', 'message' => 'Client unassigned.']);
        }
    }}