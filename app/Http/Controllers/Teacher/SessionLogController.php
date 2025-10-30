<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
// We no longer need the Subscription model here, as the Admin handles credits.
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Import the Auth facade

class SessionLogController extends Controller
{
    /**
     * Show the form for logging a completed session.
     * Laravel's Route Model Binding automatically finds the Appointment.
     */
    public function create(Appointment $appointment): View
    {
        // Professional security check: ensure the logged-in teacher owns this appointment
        if ($appointment->teacher_id !== Auth::id()) {
            abort(403); // Unauthorized access
        }

        return view('teacher.sessions.log', compact('appointment'));
    }

    /**
     * Store the submitted session log with a 'logged' status.
     */
    public function store(Request $request, Appointment $appointment)
    {
        // Professional security check
        if ($appointment->teacher_id !== Auth::id()) {
            abort(403);
        }

        // Validate all incoming data from the form
        $validated = $request->validate([
            'status' => ['required', 'in:completed,student_no_show,technical_issue'],
            'session_proof_id' => ['required', 'string', 'max:255'], // Corrected field name and rule
            'notes' => ['required', 'string', 'min:20', 'max:2000'],
        ]);

        // Update the appointment record in the database
        // We set the status to 'logged' for the admin to verify.
        $appointment->update([
            'status' => 'logged',
            'teacher_notes' => $validated['notes'],
            'session_proof_id' => $validated['session_proof_id'], // Corrected field name
        ]);
        
        // Note: The logic to decrement lesson credits has been moved
        // and will be triggered by the Admin's verification step.

        return redirect()->route('teacher.dashboard')->with('status', 'Session logged successfully and is now pending verification.');
    }
}

