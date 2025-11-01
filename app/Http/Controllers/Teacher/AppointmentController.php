<?php

    namespace App\Http\Controllers\Teacher;

    use App\Http\Controllers\Controller;
    use App\Models\Appointment;
    use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\View\View;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Validation\ValidationException;

    class AppointmentController extends Controller
    {
        /**
         * Show the form for the teacher to create a new appointment.
         */
        public function create(): View
        {
            // THIS IS THE KEY PROFESSIONAL LOGIC:
            // We fetch ONLY the clients that are assigned to the logged-in teacher.
            $clients = Auth::user()->clients()->orderBy('name')->get();
            
            return view('teacher.appointments.create', compact('clients'));
        }

        /**
         * Store a newly created appointment in storage.
         */
        public function store(Request $request): RedirectResponse
        {
            $validated = $request->validate([
                'client_id' => ['required', 'exists:users,id'],
                'date' => ['required', 'date'],
                'start_time' => ['required', 'date_format:H:i'],
                'topic' => ['required', 'string', 'max:255'],
            ]);
            
            // Professional Security Check: Ensure this teacher is allowed to book for this client
            $assignedClientIds = Auth::user()->clients()->pluck('id')->toArray();
            if (!in_array($validated['client_id'], $assignedClientIds)) {
                // Throw a validation error if the teacher tries to book for an unassigned client
                throw ValidationException::withMessages([
                    'client_id' => 'You are not authorized to book an appointment for this client.',
                ]);
            }

            $startTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
            $endTime = $startTime->copy()->addHour(); // Assuming 1-hour lessons

            // Create the appointment, automatically assigning it to the logged-in teacher
            Appointment::create([
                'client_id' => $validated['client_id'],
                'teacher_id' => Auth::id(), // Assign to the logged-in teacher
                'start_time' => $startTime,
                'end_time' => $endTime,
                'topic' => $validated['topic'],
                'subject' => 'Unknown', // Placeholder
                'status' => 'scheduled',
            ]);

            return redirect()->route('teacher.schedule.index')->with('status', 'Appointment booked successfully!');
        }
    }
    

