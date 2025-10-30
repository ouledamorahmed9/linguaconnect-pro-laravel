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
        ]);

        // Combine date and time into a single Carbon datetime object
        $startTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $endTime = $startTime->copy()->addHour(); // Assuming all lessons are 1 hour long

        // Create the appointment using our model
        Appointment::create([
            'client_id' => $validated['client_id'],
            'teacher_id' => $validated['teacher_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'topic' => $validated['topic'],
            'subject' => 'Unknown', // Placeholder subject
            'status' => 'scheduled',
        ]);

        return redirect()->route('admin.schedule.index')->with('status', 'Appointment booked successfully!');
    }
}

