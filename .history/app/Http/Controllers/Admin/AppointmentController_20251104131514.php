<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon; // <-- ** STEP 1: Import Carbon **

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
        // This is the original, simple version
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:users,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'topic' => 'required|string|max:255',

            // --- ** STEP 2: Remove end_time validation ** ---
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            // 'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time', // <-- REMOVED
            // --- ** END OF STEP 2 ** ---

            'google_meet_link' => 'nullable|url',
        ]);

        // --- ** STEP 3: Calculate end_time ** ---
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHour(); // Automatically set to 1 hour
        // --- ** END OF STEP 3 ** ---

        Appointment::create($validated + [
            'status' => 'scheduled',
            'end_time' => $endTime // <-- ** STEP 4: Add new end_time to array **
        ]);

        return redirect()->route('admin.schedule.index')->with('status', 'تم حجز الموعد بنجاح.');
    }
}

