<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\WeeklySlot;
use App\Models\Appointment;
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
        // Security: teacher owns slot
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $weeklySlot->load(['students', 'client', 'teacher']);

        // If legacy single-client slots still exist, include that student in the list
        $students = $weeklySlot->students;
        if ($students->isEmpty() && $weeklySlot->client) {
            $students = collect([$weeklySlot->client]);
        }

        // Ensure all have active subscriptions
        $inactive = $students->first(fn($s) => !$s->hasActiveSubscription());
        if ($inactive) {
            return redirect()
                ->route('teacher.schedule.index')
                ->withErrors(['message' => 'لا يمكن تسجيل حصة. أحد الطلاب لا يملك اشتراكاً نشطاً أو رصيداً كافياً.']);
        }

        return view('teacher.sessions.log', [
            'weeklySlot' => $weeklySlot,
            'students'   => $students,
        ]);
    }

    /**
     * Store a newly created session log in storage.
     */
    public function store(Request $request, WeeklySlot $weeklySlot)
    {
        // Security: teacher owns slot
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $weeklySlot->load(['students', 'client', 'teacher']);
        $students = $weeklySlot->students;
        if ($students->isEmpty() && $weeklySlot->client) {
            $students = collect([$weeklySlot->client]);
        }

        // Validate input
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'completion_status' => 'required|in:completed,no_show,technical_issue',
            'google_meet_link' => 'nullable|url',
            'extension_data' => 'nullable|string',
            'student_notes' => 'required|array',
            'student_notes.*' => 'nullable|string|max:2000',
        ]);

        // Active subscription check
        $inactive = $students->first(fn($s) => !$s->hasActiveSubscription());
        if ($inactive) {
            return redirect()
                ->route('teacher.schedule.index')
                ->withErrors(['message' => 'فشل التسجيل. أحد الطلاب لا يملك اشتراكاً نشطاً أو رصيداً كافياً.']);
        }

        // Compute session date/time for this week
        $today = Carbon::now();
        $lessonDate = $today->copy()->startOfWeek(Carbon::MONDAY);
        if ($weeklySlot->day_of_week > 0) {
            $lessonDate->addDays($weeklySlot->day_of_week - 1);
        } else {
            $lessonDate = $today->copy()->endOfWeek(Carbon::SUNDAY);
        }
        [$hour, $minute, $second] = explode(':', $weeklySlot->start_time);
        $startTime = $lessonDate->copy()->setTime($hour, $minute, $second);
        $endTime = $startTime->copy()->addHour();

        try {
            DB::transaction(function () use ($students, $validated, $weeklySlot, $startTime, $endTime) {
                foreach ($students as $student) {
                    // Prevent duplicate log for this student in this week/time
                    $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
                    $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

                    $alreadyLogged = Appointment::where('teacher_id', $weeklySlot->teacher_id)
                        ->where('client_id', $student->id)
                        ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                        ->whereTime('start_time', $weeklySlot->start_time)
                        ->whereRaw('DAYOFWEEK(start_time) = ?', [$weeklySlot->day_of_week == 0 ? 1 : $weeklySlot->day_of_week + 1])
                        ->exists();

                    if ($alreadyLogged) {
                        continue; // skip duplicates
                    }

                    Appointment::create([
                        'client_id' => $student->id,
                        'teacher_id' => $weeklySlot->teacher_id,
                        'subject' => $weeklySlot->teacher->subject ?? 'N/A',
                        'topic' => $validated['topic'],
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'status' => 'pending_verification',
                        'teacher_notes' => $validated['student_notes'][$student->id] ?? null, // per-student note
                        'google_meet_link' => $validated['google_meet_link'] ?? null,
                        'extension_data' => $validated['extension_data'] ?? null,
                        'completion_status' => $validated['completion_status'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'حدث خطأ أثناء تسجيل الحصة: ' . $e->getMessage()]);
        }

        return redirect()->route('teacher.schedule.index')->with('status', 'تم تسجيل الحصة بنجاح وفي انتظار مراجعة الإدارة.');
    }
}