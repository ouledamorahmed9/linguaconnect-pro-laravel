<?php

namespace App\Http/Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WeeklySlot;
use App\Models\Appointment; // <-- ** STEP 1: Import the Appointment model **
use Carbon\Carbon; // <-- ** STEP 2: Import Carbon **

class ScheduleController extends Controller
{
    /**
     * Display the teacher's new weekly schedule (roster).
     */
    public function index()
    {
        $teacher = Auth::user();

        // 1. Get all weekly slots for this teacher
        $weeklySlots = WeeklySlot::where('teacher_id', $teacher->id)
            ->with('client') 
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_of_week');

        // 2. Get all appointments logged THIS WEEK (Mon-Sun)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $loggedAppointments = Appointment::where('teacher_id', $teacher->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->get();

        // 3. Create a fast lookup array for the view
        // The key will be: "{client_id}-{day_of_week}-{start_time_HH:MM:SS}"
        $loggedSlotsLookup = [];
        foreach ($loggedAppointments as $appointment) {
            $dayOfWeek = $appointment->start_time->dayOfWeek; // Carbon day of week (0-6)
            $startTime = $appointment->start_time->format('H:i:s'); // "HH:MM:SS"
            $clientId = $appointment->client_id;
            
            $key = "{$clientId}-{$dayOfWeek}-{$startTime}";
            $loggedSlotsLookup[$key] = true;
        }

        // 4. Helper array for day names (as before)
        $daysOfWeek = [
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            0 => 'الأحد',
        ];

        return view('teacher.schedule.index', [
            'weeklySlots' => $weeklySlots,
            'daysOfWeek' => $daysOfWeek,
            'loggedSlotsLookup' => $loggedSlotsLookup, // <-- ** STEP 3: Pass new lookup array **
        ]);
    }
}