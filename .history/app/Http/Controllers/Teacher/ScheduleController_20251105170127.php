<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WeeklySlot;
use App\Models\Appointment; 
use Carbon\Carbon; 

class ScheduleController extends Controller
{
    /**
     * Display the teacher's new weekly schedule (roster).
     */
    public function index()
    {
        $teacher = Auth::user();

        // 1. Get all weekly slots for this teacher (for the LIST view)
        $weeklySlots = WeeklySlot::where('teacher_id', $teacher->id)
            ->with('client') 
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_of_week');

        // 2. Get appointments logged THIS WEEK (for the LIST view 'smart' button)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $loggedAppointments = Appointment::where('teacher_id', $teacher->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->get();

        // 3. Create a fast lookup array for the view
        $loggedSlotsLookup = [];
        foreach ($loggedAppointments as $appointment) {
            $dayOfWeek = $appointment->start_time->dayOfWeek; 
            $startTime = $appointment->start_time->format('H:i:s');
            $clientId = $appointment->client_id;
            
            $key = "{$clientId}-{$dayOfWeek}-{$startTime}";
            $loggedSlotsLookup[$key] = true;
        }
        
        // --- ** STEP 1: NEW LOGIC FOR GRAPHICAL CALENDAR ** ---
        // We need all slots (not grouped) for the calendar
        $allSlots = WeeklySlot::where('teacher_id', $teacher->id)
                              ->with('client')
                              ->get();
        
        $calendarEvents = $allSlots->map(function ($slot) {
            $clientName = $slot->client ? $slot->client->name : 'عميل غير محدد';
            $subject = $slot->teacher->subject ?? 'حصة';
            
            return [
                'id' => $slot->id,
                'title' => "{$clientName} ({$subject})", // e.g., "Ahmed (French)"
                'daysOfWeek' => [$slot->day_of_week], // Makes it repeating
                'startTime' => $slot->start_time,
                'endTime' => $slot->end_time,
                'color' => '#4f46e5', // Professional Indigo color
                'allDay' => false,
                'clientName' => $clientName, // For tooltip
                'subject' => $subject,     // For tooltip
            ];
        });
        // --- ** END OF NEW LOGIC ** ---

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
            'loggedSlotsLookup' => $loggedSlotsLookup, 
            'calendarEvents' => json_encode($calendarEvents), // <-- ** STEP 2: Pass new data **
        ]);
    }
}