<?php

    namespace App\Http\Controllers\Teacher;

    use App\Http\Controllers\Controller;
    use App\Models\Appointment;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\View\View;

    class ScheduleController extends Controller
    {
        /**
         * Display the teacher's schedule page.
         */
        public function index(): View
        {
            $appointments = Appointment::with('client')
                ->where('teacher_id', Auth::id())
                ->where('start_time', '>=', now()->startOfWeek())
                ->orderBy('start_time', 'asc')
                ->get()
                ->groupBy(function ($appointment) {
                    return $appointment->start_time->translatedFormat('l, d M');
                });
            
            return view('teacher.schedule.index', ['schedule' => $appointments]);
        }
    }
    

