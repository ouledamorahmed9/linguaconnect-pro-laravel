<?php

    namespace App\Http\Controllers\Teacher;

    use App\Http\Controllers\Controller;
    use App\Models\Appointment;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\View\View;

    class DashboardController extends Controller
    {
        /**
         * Display the teacher's dashboard with today's appointments.
         */
        public function index(): View
        {
            $todaysAppointments = Appointment::with('client')
                ->where('teacher_id', Auth::id())
                ->whereDate('start_time', today())
                ->orderBy('start_time', 'asc')
                ->get();
            
            return view('teacher.dashboard', compact('todaysAppointments'));
        }
    }
    
