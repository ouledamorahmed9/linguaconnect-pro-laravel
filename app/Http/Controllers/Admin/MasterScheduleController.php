<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// We use the APPOINTMENT model for this version
use App\Models\Appointment; 
use Carbon\Carbon;

class MasterScheduleController extends Controller
{
    /**
     * Display the master schedule of APPOINTMENTS for a given teacher.
     */
    public function index(Request $request)
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $selectedTeacherId = $request->input('teacher_id');
        $selectedTeacher = null;
        $calendarEvents = [];

        if ($selectedTeacherId) {
            $selectedTeacher = User::find($selectedTeacherId);

            if ($selectedTeacher && $selectedTeacher->hasRole('teacher')) {
                
                // --- THIS IS THE ORIGINAL, WORKING LOGIC ---
                // Fetch all appointments for this teacher
                $appointments = Appointment::where('teacher_id', $selectedTeacherId)
                    ->with('client') // Eager load the client's name
                    ->where('start_time', '>', Carbon::now()->subMonths(3)) // Get recent & future
                    ->get();

                // Format for FullCalendar
                $calendarEvents = $appointments->map(function ($appointment) {
                    // Check if client exists to prevent errors
                    $clientName = $appointment->client ? $appointment->client->name : 'عميل محذوف';
                    
                    return [
                        'id' => $appointment->id,
                        'title' => 'محجوز: ' . $clientName,
                        'start' => $appointment->start_time->toIso8601String(),
                        'end' => $appointment->end_time->toIso8601String(),
                        'color' => '#dc2626', // Red for booked
                        'allDay' => false,
                        // Add extra data for the tooltip
                        'clientName' => $clientName,
                        'subject' => $appointment->topic,
                    ];
                });
                // --- END OF ORIGINAL LOGIC ---

            } else {
                // If ID is invalid, reset
                $selectedTeacherId = null;
            }
        }

        return view('admin.schedule.index', [
            'teachers' => $teachers,
            'selectedTeacherId' => $selectedTeacherId,
            'selectedTeacher' => $selectedTeacher,
            'calendarEvents' => json_encode($calendarEvents), // Pass as JSON
        ]);
    }

    // --- We will not include store() or destroy() for this stable version ---
    // --- We can add them again later ---
}
