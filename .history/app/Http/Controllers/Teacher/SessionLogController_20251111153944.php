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
use Illuminate\Support\Facades\Validator; // Import Validator
use Illuminate\Validation\Rule;

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
            // This logic might need adjustment based on your exact week definition
            ->whereTime('start_time', $weeklySlot->start_time)
            ->whereRaw('DAYOFWEEK(start_time) = ?', [Carbon::parse($weeklySlot->day_of_week)->dayOfWeekIso])
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
        // Security Check
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // --- **MODIFICATION START** ---
        // Updated Validation Rules
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'completion_status' => 'required|in:completed,no_show,cancelled_by_teacher,cancelled_by_client',
            'teacher_notes' => 'nullable|string|max:2000', // Matches the blade file's "name"
            'session_proof' => 'required|json', // NEW: Validate as JSON
        ], [
            // Custom error messages
            'session_proof.required' => 'حقل إثبات الحصة (JSON) مطلوب.',
            'session_proof.json' => 'البيانات المدخلة في حقل إثبات الحصة ليست بتنسيق JSON صالح.',
            'topic.required' => 'حقل موضوع الحصة مطلوب.',
            'completion_status.required' => 'حقل حالة الحصة مطلوب.',
        ]);
        // --- **MODIFICATION END** ---

        // Calculate exact start and end times
        $lessonDate = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDays(array_search($weeklySlot->day_of_week, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']));
        list($hour, $minute, $second) = explode(':', $weeklySlot->start_time);
        $startTime = $lessonDate->copy()->setTime($hour, $minute, $second);
        $endTime = $startTime->copy()->addHour();

        try {
            DB::transaction(function () use ($weeklySlot, $validated, $startTime, $endTime) {
                Appointment::create([
                    'client_id' => $weeklySlot->client_id,
                    'teacher_id' => $weeklySlot->teacher_id,
                    'subject' => $weeklySlot->teacher->subject ?? 'N/A',
                    'topic' => $validated['topic'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => 'pending_verification', // Set default status for admin review
                    
                    // --- **MODIFICATION START** ---
                    'teacher_notes' => $validated['teacher_notes'] ?? null,
                    'session_proof' => $validated['session_proof'], // Save the JSON string
                    // 'google_meet_link' is removed, as session_proof (JSON) replaces it
                    // --- **MODIFICATION END** ---
                    
                    'completion_status' => $validated['completion_status'],
                ]);
            });
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Session Log Error: ' . $e->getMessage(), ['request' => $request->all()]);
            return redirect()->back()->withErrors(['message' => 'An error occurred while logging the session. ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('teacher.schedule.index')->with('status', 'تم تسجيل الحصة بنجاح وفي انتظار مراجعة الإدارة.');
    }
}