<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\WeeklySlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Make sure Storage is imported

class SessionLogController extends Controller
{
    /**
     * Show the form for logging a completed session.
     * (This is unchanged from your project)
     */
    public function create(WeeklySlot $weeklySlot)
    {
        // Find the next appointment for this slot that needs logging
        $appointment = Appointment::where('weekly_slot_id', $weeklySlot->id)
            ->where('teacher_id', auth()->id())
            ->where('status', 'confirmed') // Only log 'confirmed' sessions
            ->where('start_time', '<=', now()) // That have already started
            ->orderBy('start_time', 'asc')
            ->firstOrFail(); // Fails if no appointment is found

        return view('teacher.sessions.log', compact('appointment'));
    }

    /**
     * Store the session proof (File or JSON).
     */
    public function store(Request $request, Appointment $appointment)
    {
        // 1. Authorize: Make sure this teacher owns this appointment
        if ($appointment->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Validation: We need at least one of the two inputs
        $request->validate([
            'session_proof' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480', // 20MB max for video
            'session_json' => 'nullable|string|json',
        ], [
            'session_json.json' => 'The pasted text is not valid JSON.' // Custom error message
        ]);
        
        // 3. Logic: Prioritize JSON, then file
        if ($request->has('session_json') && !empty($request->input('session_json'))) {
            // ** Teacher pasted JSON **
            // We'll save this as a text string.
            // If your `session_proof` column is an ID, we would have to
            // parse the JSON and save it to `meet_reports` table first.
            // But for simplicity, we assume `session_proof` is a string column.
            
            // We'll save the JSON *string* as the proof.
            // In your admin panel, you'll see the text.
            $appointment->session_proof = $request->input('session_json');
            
        } elseif ($request->hasFile('session_proof')) {
            // ** Teacher uploaded a file **
            // This is your old, working logic
            $path = $request->file('session_proof')->store('session_proofs', 'public');
            $appointment->session_proof = $path;
        } else {
            // No proof was provided
             return back()->withErrors(['message' => 'You must upload a file or paste the JSON report.']);
        }

        // 4. Update status
        // We set it to 'pending_verification' so the Admin can check it.
        $appointment->status = 'pending_verification';
        $appointment->completion_status = 'completed'; // Mark as completed by teacher
        $appointment->save();

        // 5. Redirect
        return redirect()->route('teacher.dashboard')->with('status', 'تم تسجيل الحصة بنجاح وفي انتظار المراجعة.');
    }
}