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
use Carbon\Carbon; // Make sure to import Carbon
use Illuminate\Support\Facades\Request as FacadesRequest; // <-- Import this

class TeacherController extends Controller
{
    /**
     * Display a listing of all teachers.
     */
    public function index()
    {
        // THIS IS THE FIX:
        // The index method should retrieve ALL teachers, not one.
        // We also add pagination and client counts for a professional list.
        $teachers = User::where('role', 'teacher')
                        ->withCount('clients') // Gets the number of assigned clients
                        ->orderBy('name')
                        ->paginate(15); // Paginate the list

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
            'subject' => 'nullable|string|max:255', // <-- ** ADD THIS LINE **

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
    public function edit(Request $request, User $teacher) // Inject Request
    {
        if (!$teacher->hasRole('teacher')) {
            abort(404, 'User is not a teacher.');
        }

        // Get the search term from the request
        $search = $request->input('search');

        // 1. Get the IDs of clients currently assigned to this teacher
        $assignedClientIds = $teacher->clients()->pluck('users.id')->toArray();

        // 2. Get a paginated list of eligible clients
        $eligibleClients = User::where('role', 'client')
            ->whereHas('subscriptions', function ($query) {
                // Client must have an active subscription
                $query->where('status', 'active')
                    ->where('ends_at', '>', now())
                    ->whereColumn('lessons_used', '<', 'total_lessons');
            })
            ->when($search, function ($query, $term) {
                // Apply the search query if it exists
                // Group the search in a sub-query to not interfere with other 'where' clauses
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('name', 'like', "%{$term}%")
                             ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->paginate(20) // Paginate the results
            ->appends(['search' => $search]); // Ensure search query persists on pagination links

        // 3. Pass all data to the view
        return view('admin.teachers.edit', [
            'teacher' => $teacher,
            'eligibleClients' => $eligibleClients,
            'assignedClientIds' => $assignedClientIds,
            'search' => $search, // Pass the search term back to the view
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
