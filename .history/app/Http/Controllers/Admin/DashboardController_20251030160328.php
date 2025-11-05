<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Appointment; // Import Appointment model
    use App\Models\User; // Import User model
    use Illuminate\Http\Request;
    use Illuminate\View\View;

    class DashboardController extends Controller
    {
        /**
         * Display the main admin dashboard.
         */
        public function index(): View
        {
            // Fetch professional platform data
            $clientCount = User::where('role', 'client')->count();
            $teacherCount = User::where('role', 'teacher')->count();
            $todaysLessons = Appointment::whereDate('start_time', today())->count();
            
            // THIS IS THE NEW DATA
            $pendingSessionsCount = Appointment::where('status', 'logged')->count();

            return view('admin.dashboard', compact(
                'clientCount', 
                'teacherCount', 
                'todaysLessons', 
                'pendingSessionsCount'
            ));
        }
    }
    

