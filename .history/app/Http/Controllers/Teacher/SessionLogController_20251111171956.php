<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\WeeklySlot;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SessionLogController extends Controller
{
    /**
     * Show the form for creating a new session log.
     */
    public function create(WeeklySlot $weeklySlot)
    {
        // Security Check: Ensure the teacher owns this slot
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check subscription status
        $client = $weeklySlot->client;
        if (!$client || !$client->hasActiveSubscription()) {
            return redirect()->route('teacher.schedule.index')
                             ->withErrors(['message' => 'لا يمكن تسجيل حصة لهذا العميل. ليس لديه اشتراك نشط أو رصيد كافي.']);
        }

        // Check if a session has already been logged for this slot *this week*
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $alreadyLogged = Appointment::where('teacher_id', $weeklySlot->teacher_id)
            ->where('client_id', $weeklySlot->client_id)
            ->where('start_time', '>=', $startOfWeek)
            ->where('start_time', '<=', $endOfWeek)
            ->whereTime('start_time', $weeklySlot->start_time)
            ->exists();

        if ($alreadyLogged) {
            return redirect()->route('teacher.schedule.index')
                             ->withErrors(['message' => 'لقد قمت بالفعل بتسجيل حصة لهذا الموعد هذا الأسبوع.']);
        }

        return view('teacher.sessions.log', compact('weeklySlot'));
    }

    /**
     * Store a newly created session log in storage.
     */
    public function store(Request $request, WeeklySlot $weeklySlot)
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'completion_status' => 'required|in:completed,no_show_teacher,no_show_client',
            'notes' => 'nullable|string',
            'session_proof' => 'nullable|url|max:1000',
            
            // --- !! NEW FEATURE !! ---
            'session_json' => 'nullable|string|json', 
            // --- !! END NEW FEATURE !! ---

        ], [
            // --- !! NEW FEATURE !! ---
            'session_json.json' => 'The pasted text is not valid JSON.',
            // --- !! END NEW FEATURE !! ---
        ]);

        // Calculate the exact start and end time for this week's session
        $today = Carbon::now();
        $lessonDate = $today->copy()->setISODate($today->year, $today->weekOfYear, $weeklySlot->day_of_week);
        
        list($hour, $minute, $second) = explode(':', $weeklySlot->start_time);
        $startTime = $lessonDate->copy()->setTime($hour, $minute, $second);
        $endTime = $startTime->copy()->addHour();

        try {
            DB::transaction(function () use ($weeklySlot, $validated, $startTime, $endTime) {
                
                // --- !! NEW LOGIC !! ---
                // Prioritize JSON, then the URL link
                $session_proof_data = null;
                if (!empty($validated['session_json'])) {
                    $session_proof_data = $validated['session_json'];
                } elseif (!empty($validated['session_proof'])) {
                    // This is the file path (URL)
                    $session_proof_data = $validated['session_proof'];
                }
                // --- !! END NEW LOGIC !! ---

                Appointment::create([
                    'client_id' => $weeklySlot->client_id,
                    'teacher_id' => $weeklySlot->teacher_id,
                    'subject' => $weeklySlot->teacher->subject ?? 'N/A',
                    'topic' => $validated['topic'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => 'pending_verification', 
                    
                    'teacher_notes' => $validated['notes'] ?? null,
                    
                    // --- !! BUG FIX & NEW LOGIC !! ---
                    // We save the file/JSON to session_proof
                    // and the *actual* Google Meet link to google_meet_link
                    'session_proof' => $session_proof_data,
                    'google_meet_link' => $weeklySlot->google_meet_link ?? null,
                    // --- !! END OF FIX & NEW LOGIC !! ---
                    
                    'completion_status' => $validated['completion_status'],
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'An error occurred while logging the session. ' . $e->getMessage()]);
        }

        return redirect()->route('teacher.schedule.index')->with('status', 'تم تسجيل الحصة بنجاح وفي انتظار مراجعة الإدارة.');
    }
}