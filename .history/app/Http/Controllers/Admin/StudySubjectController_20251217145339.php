<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudySubject;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudySubjectController extends Controller
{
    /**
     * Display a listing of the study subjects.
     */
    public function index(): View
    {
        $subjects = StudySubject::ordered()->withCount('users')->get();
        
        return view('admin.study-subjects. index', compact('subjects'));
    }

    /**
     * Show the form for creating a new study subject.
     */
    public function create(): View
    {
        return view('admin.study-subjects.create');
    }

    /**
     * Store a newly created study subject in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:study_subjects,name'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['required', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        StudySubject::create($validated);

        return redirect()->route('admin.study-subjects.index')
            ->with('success', 'Study subject created successfully! ');
    }

    /**
     * Show the form for editing the specified study subject.
     */
    public function edit(StudySubject $studySubject): View
    {
        return view('admin.study-subjects.edit', compact('studySubject'));
    }

    /**
     * Update the specified study subject in storage.
     */
    public function update(Request $request, StudySubject $studySubject): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:study_subjects,name,' . $studySubject->id],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['required', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $studySubject->update($validated);

        return redirect()->route('admin.study-subjects.index')
            ->with('success', 'Study subject updated successfully!');
    }

    /**
     * Remove the specified study subject from storage.
     */
    public function destroy(StudySubject $studySubject): RedirectResponse
    {
        // Check if any users are using this subject
        if ($studySubject->users()->count() > 0) {
            return redirect()->route('admin.study-subjects.index')
                ->with('error', 'Cannot delete this subject because it is assigned to ' . $studySubject->users()->count() . ' user(s).');
        }

        $studySubject->delete();

        return redirect()->route('admin.study-subjects.index')
            ->with('success', 'Study subject deleted successfully!');
    }
}