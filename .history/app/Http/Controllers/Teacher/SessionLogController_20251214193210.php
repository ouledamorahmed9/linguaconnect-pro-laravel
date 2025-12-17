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
    public function create(WeeklySlot $weeklySlot)
    {
        $teacher = Auth::user();

        if ($weeklySlot->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized action.');
        }

        $availableStudents = $teacher->clients()->orderBy('name')->get();

        return view('teacher.sessions.log', [
            'weeklySlot' => $weeklySlot,
            'availableStudents' => $availableStudents
        ]);
    }

    public function store(Request $request, WeeklySlot $weeklySlot)
    {
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'teacher_notes' => 'nullable|string',
            'completion_status' => 'required|in:completed,no_show,technical_issue',
            'google_meet_link' => 'nullable|url',
            // This accepts the encrypted string
            'extension_data' => 'nullable|string', 
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ]);

        // Calculate Time
        $today = Carbon::now();
        $lessonDate = $today->copy()->startOfWeek(Carbon::MONDAY);
        if ($weeklySlot->day_of_week > 0) {
            $lessonDate->addDays($weeklySlot->day_of_week - 1);
        } else {
            $lessonDate = $today->copy()->endOfWeek(Carbon::SUNDAY);
        }

        list($hour, $minute, $second) = explode(':', $weeklySlot->start_time);
        $startTime = $lessonDate->copy()->setTime($hour, $minute, $second);
        $endTime = $startTime->copy()->addHour();

        try {
            DB::transaction(function () use ($weeklySlot, $validated, $startTime, $endTime) {
                
                // Note: We are saving the encrypted string directly to the database.
                // Decryption usually happens when the Admin VIEWS the record, not when saving.
                // This ensures the raw proof is stored exactly as submitted.
                
                $appointment = Appointment::create([
                    'client_id' => $weeklySlot->client_id,
                    'teacher_id' => $weeklySlot->teacher_id,
                    'subject' => $weeklySlot->teacher->subject ?? 'N/A',
                    'topic' => $validated['topic'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => 'pending_verification',
                    'teacher_notes' => $validated['teacher_notes'] ?? null,
                    'google_meet_link' => $validated['google_meet_link'] ?? null,
                    'extension_data' => $validated['extension_data'] ?? null,
                    'completion_status' => $validated['completion_status'],
                ]);

                $appointment->students()->attach($validated['student_ids']);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Error logging session: ' . $e->getMessage()]);
        }

        return redirect()->route('teacher.schedule.index')->with('status', 'Session logged successfully.');
    }
}