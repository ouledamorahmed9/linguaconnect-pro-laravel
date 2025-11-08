<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\WeeklySlot; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class SessionLogController extends Controller
{
    /**
     * Show the form for logging a session based on a weekly slot.
     */
    public function create(WeeklySlot $weeklySlot)
    {
        // ** STEP 1: Load client and check subscription **
        $weeklySlot->load('client.subscriptions', 'teacher');

        // Professional Check: Ensure the teacher owns this slot
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403);
        }
        
        // ** NEW PROFESSIONAL CHECK **
        // Block logging if the client's subscription is not active
        if (!$weeklySlot->client || !$weeklySlot->client->hasActiveSubscription()) {
            return redirect()->route('teacher.schedule.index')
                             ->withErrors(['message' => 'لا يمكن تسجيل حصة لعميل اشتراكه غير نشط.']);
        }
        // ** END OF CHECK **

        return view('teacher.sessions.log', [
            'weeklySlot' => $weeklySlot
        ]);
    }

    /**
     * Store a new session log (Appointment) in storage.
     */
    public function store(Request $request, WeeklySlot $weeklySlot)
    {
        $validated = $request->validate([
            'session_date' => 'required|date',
            'completion_status' => [
                'required', 
                'string', 
                Rule::in(['completed', 'student_absent', 'technical_issue'])
            ],
            'topic' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'session_proof' => 'nullable|string|max:1000',
        ]);

        $teacher = Auth::user();
        if ($weeklySlot->teacher_id !== $teacher->id) {
            abort(403);
        }
        
        // ** NEW PROFESSIONAL CHECK (redundant, but good for safety) **
        if (!$weeklySlot->client || !$weeklySlot->client->hasActiveSubscription()) {
            return redirect()->route('teacher.schedule.index')
                             ->withErrors(['message' => 'لا يمكن تسجيل حصة لعميل اشتراكه غير نشط.']);
        }
        // ** END OF CHECK **

        $sessionDate = Carbon::parse($validated['session_date']);
        
        $days = [ 0 => 'الأحد', 1 => 'الاثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'];
        $slotDayName = $days[$weeklySlot->day_of_week];

        if ($sessionDate->dayOfWeek != $weeklySlot->day_of_week) {
             return redirect()->back()
                ->withInput()
                ->withErrors(['session_date' => 'التاريخ المختار لا يتطابق مع يوم الحصة (يجب أن يكون يوم ' . $slotDayName . ').']);
        }

        $startTime = $sessionDate->copy()->setTimeFromTimeString($weeklySlot->start_time);
        $endTime = $sessionDate->copy()->setTimeFromTimeString($weeklySlot->end_time);
        
        $alreadyLogged = Appointment::where('teacher_id', $teacher->id)
            ->where('client_id', $weeklySlot->client_id)
            ->where('start_time', $startTime)
            ->exists();
            
        if ($alreadyLogged) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['session_date' => 'لقد قمت بتسجيل حصة لهذا العميل في هذا التاريخ والوقت مسبقًا.']);
        }

        Appointment::create([
            'client_id' => $weeklySlot->client_id,
            'teacher_id' => $teacher->id,
            'subject' => $teacher->subject ?? 'N/A',
            'topic' => $validated['topic'],
            'completion_status' => $validated['completion_status'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'teacher_notes' => $validated['notes'],
            'session_proof_id' => $validated['session_proof'],
            'status' => 'pending_verification',
            'google_meet_link' => null,
        ]);

        return redirect()->route('teacher.schedule.index')->with('status', 'تم تسجيل الحصة بنجاح، وهي الآن بانتظار مراجعة المدير.');
    }
}