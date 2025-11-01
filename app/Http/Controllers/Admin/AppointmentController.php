<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon; // Import Carbon for date handling
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Show the form for creating a new appointment.
     */
    public function create(): View
    {
        $clients = User::where('role', 'client')->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();

        return view('admin.appointments.create', compact('clients', 'teachers'));
    }

    /**
     * Store a newly created appointment in storage.
     */
        public function store(Request $request)
        {
            // Professional validation for all incoming data
            $validated = $request->validate([
                'client_id' => ['required', 'exists:users,id'],
                'teacher_id' => ['required', 'exists:users,id'],
                'date' => ['required', 'date'],
                'start_time' => ['required', 'date_format:H:i'],
                'topic' => ['required', 'string', 'max:255'],
                'google_meet_link' => ['required', 'url', 'max:255'], // Add this rule
            ]);

            $startTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
            $endTime = $startTime->copy()->addHour();

            Appointment::create([
                'client_id' => $validated['client_id'],
                'teacher_id' => $validated['teacher_id'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'topic' => $validated['topic'],
                'google_meet_link' => $validated['google_meet_link'], // Add this line
                'subject' => 'Unknown',
                'status' => 'scheduled',
            ]);

            return redirect()->route('admin.schedule.index')->with('status', 'Appointment booked successfully!');
        }
}

