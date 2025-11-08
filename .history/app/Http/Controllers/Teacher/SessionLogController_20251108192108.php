<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\WeeklySlot;
use App\Models\Appointment;
use App\Models\User; // <-- ** ADDED THIS IMPORT **
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

        // --- ** THIS IS THE MODIFICATION ** ---
        // Load the client and check their subscription status
        $client = $weeklySlot->client;

        if (!$client || !$client->hasActiveSubscription()) {
            return redirect()->route('teacher.schedule.index')
                             ->withErrors(['message' => 'لا يمكن تسجيل حصة لهذا العميل. ليس لديه اشتراك نشط أو رصيد كافي.']);
        }
        // --- ** END OF MODIFICATION ** ---

        // Check if a session has already been logged for this slot *this week*
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $alreadyLogged = Appointment::where('teacher_id', $weeklySlot->teacher_id)
            ->where('client_id', $weeklySlot->client_id)
            ->where('start_time', '>=', $startOfWeek)
            ->where('start_time', '<=', $endOfWeek)
            ->whereTime('start_time', $weeklySlot->start_time)
            ->where(function ($query) use ($weeklySlot) {
                // Check Carbon day of week (Mon=1, Sun=0)
                // Note: MySQL DAYOFWEEK() is 1=Sun, 2=Mon... 7=Sat
                // We adjust for Carbon's Mon=1, Sun=0
                $mysqlDayOfWeek = $weeklySlot->day_of_week + 1;
                if ($weeklySlot->day_of_week == 0) {
                    $mysqlDayOfWeek = 1; // Carbon Sunday (0) is MySQL Sunday (1)
                }
                
                $query->whereRaw('DAYOFWEEK(start_time) = ?', [$mysqlDayOfWeek]);
            })
            ->exists();

        if ($alreadyLogged) {
            return redirect()->route('teacher.schedule.index')
                             ->withErrors(['message' => 'لقد قمت بتسجيل هذه الحصة بالفعل لهذا الأسبوع.']);
        }


        return view('teacher.sessions.log', [
            'weeklySlot' => $weeklySlot
        ]);
    }

    /**
     * Store a newly created session log in storage.
     */
    public function store(Request $request, WeeklySlot $weeklySlot)
    {
        // Security Check: Ensure the teacher owns this slot
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // --- ** THIS IS THE MODIFICATION (DOUBLE CHECK) ** ---
        // Re-check subscription status on submission. This is a critical
        // backend validation in case the client's status changed.
        $client = $weeklySlot->client;

        if (!$client || !$client->hasActiveSubscription()) {
            return redirect()->route('teacher.schedule.index')
                             ->withErrors(['message' => 'فشل التسجيل. ليس لدى العميل اشتراك نشط أو رصيد كافي.']);
        }
        // --- ** END OF MODIFICATION ** ---

        // Check if a session has already been logged for this slot *this week*
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $alreadyLogged = Appointment::where('teacher_id', $weeklySlot->teacher_id)
            ->where('client_id', $weeklySlot->client_id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->whereTime('start_time', $weeklySlot->start_time)
            ->where(function ($query) use ($weeklySlot) {
                // Note: MySQL DAYOFWEEK() is 1=Sun, 2=Mon... 7=Sat
                $mysqlDayOfWeek = $weeklySlot->day_of_week + 1;
                if ($weeklySlot->day_of_week == 0) {
                    $mysqlDayOfWeek = 1; // Carbon Sunday (0) is MySQL Sunday (1)
                }
                
                $query->whereRaw('DAYOFWEEK(start_time) = ?', [$mysqlDayOfWeek]);
            })
            ->exists();

        if ($alreadyLogged) {
            return redirect()->route('teacher.schedule.index')
                             ->withErrors(['message' => 'لقد قمت بتسجيل هذه الحصة بالفعل لهذا الأسبوع.']);
        }

        // --- All checks passed, proceed to log the session ---

        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'teacher_notes' => 'nullable|string',
            'completion_status' => 'required|in:completed,no_show,technical_issue',
            'google_meet_link' => 'nullable|url',
        ]);

        // Calculate the exact start and end time for the logged session
        $today = Carbon::now();
        $lessonDate = $today->copy()->startOfWeek(Carbon::MONDAY)->addDays($weeklySlot->day_of_week - 1); // -1 because Carbon Mon=1
        
        if ($weeklySlot->day_of_week == 0) { // Handle Sunday
            $lessonDate = $today->copy()->endOfWeek(Carbon::SUNDAY);
        }

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
                    'status' => 'pending_verification', 
                    'teacher_notes' => $validated['teacher_notes'],
                    'google_meet_link' => $validated['google_meet_link'],
                    'completion_status' => $validated['completion_status'],
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'An error occurred while logging the session. ' . $e->getMessage()]);
        }

        return redirect()->route('teacher.schedule.index')->with('status', 'تم تسجيل الحصة بنجاح وفي انتظار مراجعة الإدارة.');
    }
}