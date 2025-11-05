<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\WeeklySlot; // <-- ** STEP 1: Import WeeklySlot **
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SessionLogController extends Controller
{
    /**
     * Show the form for logging a session based on a weekly slot.
     *
     * @param  \App\Models\WeeklySlot  $weeklySlot
     * @return \Illuminate\View\View
     */
    public function create(WeeklySlot $weeklySlot)
    {
        // ** STEP 2: Load the slot and its relationships **
        $weeklySlot->load('client', 'teacher');

        // Professional Check: Ensure the teacher owns this slot
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403);
        }

        return view('teacher.sessions.log', [
            'weeklySlot' => $weeklySlot
        ]);
    }

    /**
     * Store a new session log (Appointment) in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WeeklySlot  $weeklySlot
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, WeeklySlot $weeklySlot)
    {
        // ** STEP 3: Validate the form data **
        $validated = $request->validate([
            'session_date' => 'required|date',
            'topic' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'session_proof' => 'nullable|string|max:1000', // Or 'file' if you handle uploads
        ]);

        $teacher = Auth::user();
        
        // Professional Check: Ensure the teacher owns this slot
        if ($weeklySlot->teacher_id !== $teacher->id) {
            abort(403);
        }

        // --- ** STEP 4: Calculate the exact start and end time ** ---
        $sessionDate = Carbon::parse($validated['session_date']);
        
        // Check if the chosen date matches the day of the week for the slot
        // 0=Sun, 1=Mon... Carbon's dayOfWeek matches this.
        if ($sessionDate->dayOfWeek != $weeklySlot->day_of_week) {
             return redirect()->back()
                ->withInput()
                ->withErrors(['session_date' => 'التاريخ المختار لا يتطابق مع يوم الحصة (e.g., must be a ' . $weeklySlot->day_of_week_name . ').']);
        }

        $startTime = $sessionDate->copy()->setTimeFromTimeString($weeklySlot->start_time);
        $endTime = $sessionDate->copy()->setTimeFromTimeString($weeklySlot->end_time);
        
        // Professional Check: Has this exact slot already been logged?
        $alreadyLogged = Appointment::where('teacher_id', $teacher->id)
            ->where('client_id', $weeklySlot->client_id)
            ->where('start_time', $startTime)
            ->exists();
            
        if ($alreadyLogged) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['session_date' => 'لقد قمت بتسجيل حصة لهذا العميل في هذا التاريخ والوقت مسبقًا.']);
        }
        // --- ** END OF CHECKS ** ---


        // ** STEP 5: Create the new Appointment (as a log) **
        Appointment::create([
            'client_id' => $weeklySlot->client_id,
            'teacher_id' => $teacher->id,
            'subject' => $teacher->subject ?? 'N/A', // Get subject from teacher's profile
            'topic' => $validated['topic'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'notes' => $validated['notes'],
            'session_proof' => $validated['session_proof'],
            'status' => 'pending_verification', // <-- Send to admin for review
            'google_meet_link' => null, // Not needed for a log
        ]);

        return redirect()->route('teacher.schedule.index')->with('status', 'تم تسجيل الحصة بنجاح، وهي الآن بانتظار مراجعة المدير.');
    }
}
