<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // THIS VERSION INCLUDES THE FIX FOR THE "AMBIGUOUS ID" BUG
        $teacher = Auth::user();
        
        // This uses the correct select() to avoid ambiguity
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
        // THIS VERSION INCLUDES THE FIX FOR THE "AMBIGUOUS ID" BUG
        $teacher = Auth::user();
        
        $validated = $request->validate([
            'client_id' => [
                'required',
                'integer',
                // This rule ensures a teacher can only book for their *own* clients
                Rule::in($teacher->clients()->pluck('users.id')),
            ],
            'subject' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'google_meet_link' => 'nullable|url',
        ]);

        // Add the authenticated teacher's ID
        $validatedData = $validated + [
            'teacher_id' => $teacher->id,
            'status' => 'scheduled'
        ];

        Appointment::create($validatedData);

        return redirect()->route('teacher.schedule.index')->with('status', 'تم حجز الموعد بنجاح!');
    }
}

