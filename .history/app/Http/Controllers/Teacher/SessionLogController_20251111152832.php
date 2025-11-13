<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; // Import Validator
use Illuminate\Validation\Rule; // Import Rule

class SessionLogController extends Controller
{
    /**
     * Show the form for logging a new session.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $teacher = Auth::user();
        
        // Get appointments that are confirmed but not yet marked as completed
        // and are in the past (or within a reasonable window).
        $appointments = Appointment::where('teacher_id', $teacher->id)
            ->where('status', 'confirmed')
            ->where('completion_status', 'pending')
            ->where('start_time', '<=', now()) // Only show appointments that have started
            ->with('client')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('teacher.sessions.log', compact('appointments'));
    }

    /**
     * Store a newly created session log in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // --- Validation ---
        // We add a custom validation rule to check if the input is valid JSON.
        $validator = Validator::make($request->all(), [
            'appointment_id' => [
                'required',
                // Ensure the appointment exists and belongs to this teacher
                Rule::exists('appointments', 'id')->where(function ($query) {
                    $query->where('teacher_id', Auth::id());
                })
            ],
            'session_proof' => 'required|string|json', // Validate that it is a valid JSON string
            'notes' => 'nullable|string|max:1000',
        ], [
            'session_proof.json' => 'The session proof data is not valid JSON. Please copy the text exactly from the extension.',
            'appointment_id.exists' => 'The selected appointment is not valid or does not belong to you.',
        ]);

        // Redirect back with errors if validation fails
        if ($validator->fails()) {
            return redirect()->route('teacher.sessions.log')
                        ->withErrors($validator)
                        ->withInput();
        }

        // --- Process Valid Data ---
        $validatedData = $validator->validated();

        try {
            $appointment = Appointment::findOrFail($validatedData['appointment_id']);

            // Double-check authorization (though validation should cover it)
            if ($appointment->teacher_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            // Update the appointment record
            $appointment->completion_status = 'pending_verification';
            $appointment->session_proof = $validatedData['session_proof']; // Save the raw JSON string
            $appointment->notes = $validatedData['notes'] ?? null;
            $appointment->save();

            // Redirect with a clear success message
            return redirect()->route('teacher.dashboard')->with('success', 'Session proof submitted! It is now pending admin verification.');

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error logging session proof: ' . $e->getMessage());

            // Send user back with a helpful error
            return redirect()->route('teacher.sessions.log')
                        ->with('error', 'An error occurred while saving the session proof. Please try again.')
                        ->withInput();
        }
    }
}