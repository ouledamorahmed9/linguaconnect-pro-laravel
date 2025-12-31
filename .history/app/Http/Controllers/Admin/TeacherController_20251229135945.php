<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudySubject; // Ensure this is imported
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
    public function index(Request $request)
    {
        $query = User::where('role', 'teacher');

        // Search Logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $teachers = $query->withCount('clients')
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
        $studySubjects = StudySubject::active()->ordered()->get();
        return view('admin.teachers.create', compact('studySubjects'));
    }

    /**
     * Store a newly created teacher in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'], // <--- Validating Phone
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'subject' => ['nullable', 'string', 'max:255'],
        ]);

        $teacher = new User();
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->phone = $request->phone; // <--- Saving Phone
        $teacher->password = Hash::make($request->password);
        $teacher->role = 'teacher';
        $teacher->subject = $request->subject;
        
        $teacher->save();

        return redirect()->route('admin.teachers.index')
            ->with('status', 'Teacher created successfully.');
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Request $request, User $teacher)
    {
        if (!$teacher->hasRole('teacher')) {
            abort(404, 'User is not a teacher.');
        }

        // Fetch Subjects for the Dropdown
        $studySubjects = StudySubject::active()->ordered()->get();

        $search = $request->input('search');
        $assignedClientIds = $teacher->clients()->pluck('users.id')->toArray();

$clients = User::role('client')
        ->whereHas('subscriptions', function ($query) {
            $query->where('status', 'active')
                  ->where('ends_at', '>', now()); // Ensure it hasn't expired
        })
        ->get();
                    ->when($search, function ($query, $term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('name', 'like', "%{$term}%")
                             ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->appends(['search' => $search]);

        return view('admin.teachers.edit', [
            'teacher' => $teacher,
            'eligibleClients' => $eligibleClients,
            'assignedClientIds' => $assignedClientIds,
            'search' => $search,
            'studySubjects' => $studySubjects,
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
            'phone' => ['required', 'string', 'max:20'], // <--- Validating Phone
            'subject' => ['nullable', 'string', 'max:255'],
        ]);

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone, // <--- Updating Phone
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

        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher deleted successfully!');
    }
}