<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon; // Make sure Carbon is imported

class AppointmentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // This is the original, simple version
        $clients = User::where('role', 'client')->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        
        return view('admin.appointments.create', compact('clients', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // This is the final, merged version
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:users,id',
            'teacher_id' => 'required|integer|exists:users,id',
            // 'subject' => 'required|string|max:255', // <-- REMOVED
            'topic' => 'required|string|max:255',
            'start_time' => 'required|date_format:Y-m-d\TH:i', // <-- FIX for datetime-local
            // 'end_time' validation removed
            'google_meet_link' => 'nullable|url',
        ]);

        // --- Calculate End Time (1 hour lesson) ---
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHour(); // Automatically set to 1 hour
        
        // --- Get Subject from Teacher ---
        $teacher = User::find($validated['teacher_id']);
        $subject = $teacher->subject ?? 'N/A'; // Get subject or use 'N/A' as fallback

        // Create the appointment with all data
        Appointment::create($validated + [
            'status' => 'scheduled',
            'end_time' => $endTime, // Add calculated end_time
            'subject' => $subject  // Add automatic subject
        ]);

        return redirect()->route('admin.schedule.index')->with('status', 'تم حجز الموعد بنجاح.');
    }
}

