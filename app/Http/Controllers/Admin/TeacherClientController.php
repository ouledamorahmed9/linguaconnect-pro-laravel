<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TeacherClientController extends Controller
{
    /**
     * Sync the list of clients assigned to a specific teacher.
     * This is a very professional and efficient way to manage relationships.
     */
    public function sync(Request $request, User $teacher): RedirectResponse
    {
        // We only want to sync clients, so we ensure the user is a teacher
        if (!$teacher->hasRole('teacher')) {
            abort(404);
        }

        // Get the list of client IDs from the form, or an empty array if none are selected
        $clientIds = $request->input('clients', []);

        // "sync" is a powerful Laravel method that automatically
        // adds and removes clients from the pivot table to match the list.
        $teacher->clients()->sync($clientIds);

        return redirect()->back()->with('status', 'Client list for ' . $teacher->name . ' has been updated successfully!');
    }
}

