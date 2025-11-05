<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon; // <-- Make sure Carbon is imported

class AppointmentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teacher = Auth::user();
        
        $clients = $teacher->clients()
                           ->select('users.id', 'users.name')
                           ->orderBy('name')
                           ->get();

        return view('teacher.appointments.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $teacher = Auth::user();
        
        $validated = $request->validate([
            'client_id' => [
                'required',
                'integer',
                Rule::in($teacher->clients()->pluck('users.id')),
            ],
            'topic' => 'required|string|max:255',
            'start_time' => 'required|date', // This validation rule is correct
            'google_meet_link' => 'nullable|url',
        ]);

        // --- THIS IS THE FIX ---

        // 1. Parse the start time into a Carbon object
        $startTime = Carbon::parse($validated['start_time']);
        // 2. Calculate the end time
        $endTime = $startTime->copy()->addHour(); 

        $validatedData = $validated + [
            'teacher_id' => $teacher->id,
            'status' => 'scheduled',
            'start_time' => $startTime, // <-- 3. OVERWRITE the string with the Carbon object
            'end_time' => $endTime,
            'subject' => $teacher->subject ?? 'N/A'
        ];
        
        // --- END OF FIX ---

        Appointment::create($validatedData);

        return redirect()->route('teacher.schedule.index')->with('status', 'تم حجز الموعد بنجاح!');
    }
}

