<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\NextAppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Carbon\Carbon; // <-- ** STEP 1: Import Carbon **

class DashboardController extends Controller
{
    protected $nextAppointmentService;

    public function __construct(NextAppointmentService $nextAppointmentService)
    {
        $this->nextAppointmentService = $nextAppointmentService;
    }

    public function index()
    {
        $teacher = Auth::user(); // Get the authenticated teacher

        // Get the next appointment (existing logic)
        $nextAppointment = $this->nextAppointmentService->getNextAppointmentForTeacher($teacher);

        // --- All-time stats logic (existing) ---
        $totalVerifiedHours = Appointment::where('teacher_id', $teacher->id)
                                         ->where('status', 'verified')
                                         ->count();

        $totalPendingHours = Appointment::where('teacher_id', $teacher->id)
                                        ->where('status', 'pending_verification')
                                        ->count();
        
        // --- ** STEP 2: NEW WEEKLY STATS LOGIC ** ---
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        
        // Count all sessions logged this week (Mon-Sun) that are not disputed
        $hoursThisWeek = Appointment::where('teacher_id', $teacher->id)
            ->whereIn('status', ['verified', 'pending_verification'])
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->count();
        // --- ** END OF NEW LOGIC ** ---


        return view('teacher.dashboard', [
            'nextAppointment' => $nextAppointment,
            'totalVerifiedHours' => $totalVerifiedHours,
            'totalPendingHours' => $totalPendingHours,
            'hoursThisWeek' => $hoursThisWeek, // <-- ** STEP 3: Pass new data **
        ]);
    }
}