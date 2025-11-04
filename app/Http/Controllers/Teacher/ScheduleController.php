<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Make sure Carbon is imported

class ScheduleController extends Controller
{
    /**
     * Display the teacher's schedule on a graphical calendar.
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();

        // 1. Fetch appointments for a wider date range to fill the calendar
        // We'll get appointments from the last 30 days and for the next 60 days
        $appointments = $teacher->appointments()
                              ->with('client') // Eager load the client data
                              ->where('start_time', '>=', Carbon::now()->subDays(30))
                              ->where('start_time', '<=', Carbon::now()->addDays(60))
                              ->get();

        // 2. Format the appointments into a JSON structure for FullCalendar
        $calendarEvents = $appointments->map(function ($appointment) {
            
            // Determine event color based on status
            $color = '#4f46e5'; // Default: Indigo (scheduled)
            if ($appointment->status === 'completed') {
                $color = '#16a34a'; // Green
            } elseif ($appointment->status === 'cancelled') {
                $color = '#dc2626'; // Red
            }

            return [
                'id'        => $appointment->id,
                'title'     => $appointment->client->name . ' - ' . $appointment->topic,
                'start'     => $appointment->start_time->toIso8601String(),
                'end'       => $appointment->end_time->toIso8601String(),
                'color'     => $color,
                'url'       => $appointment->google_meet_link, // Makes the event clickable to join
                'extendedProps' => [
                    'subject' => $appointment->subject,
                    'client' => $appointment->client->name,
                    'status' => $appointment->status,
                    'meetLink' => $appointment->google_meet_link,
                    'logUrl' => route('teacher.sessions.log.create', $appointment)
                ]
            ];
        });

        // 3. Pass the formatted data to the view
        return view('teacher.schedule.index', [
            'calendarEvents' => $calendarEvents
        ]);
    }
}
