<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display the teacher's graphical schedule page.
     */
    public function index(): View
    {
        $teacher = Auth::user();
        $today = Carbon::now();
        $startOfWeek = $today->startOfWeek(Carbon::SATURDAY)->format('Y-m-d H:i:s');
        $endOfWeek = $today->endOfWeek(Carbon::FRIDAY)->format('Y-m-d H:i:s');

        // 1. Fetch all appointments for the current week
        $appointments = $teacher->appointments()
            ->with('client')
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->get();

        // 2. Create the professional "lookup map"
        // This makes the view file much cleaner.
        $appointmentsMap = [];
        foreach ($appointments as $appointment) {
            $day = $appointment->start_time->dayOfWeek; // 0=Sun, 1=Mon...
            $hour = $appointment->start_time->format('H'); // "09", "10"
            $appointmentsMap[$day][$hour] = $appointment;
        }

        // 3. Define the days and time slots for our graphical calendar
        $days = [
            1 => 'الإثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            0 => 'الأحد',
        ];

        // e.g., 8:00 AM to 8:00 PM
        $timeSlots = [];
        for ($hour = 8; $hour <= 20; $hour++) {
            $timeSlots[] = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
        }
        
        return view('teacher.schedule.index', [
            'days' => $days,
            'timeSlots' => $timeSlots,
            'appointmentsMap' => $appointmentsMap,
        ]);
    }
}

