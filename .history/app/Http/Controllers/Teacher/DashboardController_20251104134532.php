<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\NextAppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import Auth
use App\Models\Appointment; // <-- Import Appointment

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

        // --- NEW PROFESSIONAL STATS LOGIC ---
        // Since lessons are 1 hour, we just count them.

        // 1. Get count of VERIFIED hours
        // These are lessons that are finished AND approved by admin
        $totalVerifiedHours = Appointment::where('teacher_id', $teacher->id)
                                         ->where('status', 'verified')
                                         ->count();

        // 2. Get count of PENDING hours
        // These are lessons that are finished but NOT yet approved by admin
        $totalPendingHours = Appointment::where('teacher_id', $teacher->id)
                                        ->where('status', 'pending_verification')
                                        ->count();
        // --- END NEW LOGIC ---

        return view('teacher.dashboard', [
            'nextAppointment' => $nextAppointment,
            'totalVerifiedHours' => $totalVerifiedHours,   // <-- Pass new data
            'totalPendingHours' => $totalPendingHours, // <-- Pass new data
        ]);
    }
}

