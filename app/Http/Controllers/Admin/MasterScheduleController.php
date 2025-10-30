<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Appointment; // Import the Appointment model
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\View\View;

    class MasterScheduleController extends Controller
    {
        /**
         * Display the master schedule.
         */
        public function index(): View
        {
            // Fetch appointments for the current week (e.g., from Monday to Sunday)
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $appointments = Appointment::with(['teacher', 'client']) // Eager load relationships for efficiency
                ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                ->orderBy('start_time')
                ->get()
                ->groupBy(function ($appointment) {
                    // Group appointments by day for the calendar view
                    return $appointment->start_time->translatedFormat('l, d');
                });

            return view('admin.schedule.index', [
                'calendarData' => $appointments,
                'startOfWeek' => $startOfWeek,
                'endOfWeek' => $endOfWeek,
            ]);
        }
    }
    

