<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon; 

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
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:users,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'topic' => 'required|string|max:255',
            'start_time' => 'required|date', // This validation rule is correct
            'google_meet_link' => 'nullable|url',
        ]);

        // --- THIS IS THE FIX ---

        // 1. Parse the start time into a Carbon object
        $startTime = Carbon::parse($validated['start_time']);
        // 2. Calculate the end time
        $endTime = $startTime->copy()->addHour(); 

        // Get the teacher and their subject
        $teacher = User::find($validated['teacher_id']);
        $subject = $teacher->subject ?? 'N/A'; 

        Appointment::create($validated + [
            'status' => 'scheduled',
            'start_time' => $startTime, // <-- 3. OVERWRITE the string with the Carbon object
            'end_time' => $endTime,
            'subject' => $subject
        ]);

        // --- END OF FIX ---

        return redirect()->route('admin.schedule.index')->with('status', 'تم حجز الموعد بنجاح.');
    }
}

