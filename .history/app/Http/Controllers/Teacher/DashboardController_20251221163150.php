<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\NextAppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Carbon\Carbon;
use App\Models\WeeklySlot;

class DashboardController extends Controller
{
    protected $nextAppointmentService;

    public function __construct(NextAppointmentService $nextAppointmentService)
    {
        $this->nextAppointmentService = $nextAppointmentService;
    }

    public function index()
    {
        $teacher = Auth::user();

        // Get the next appointment (existing logic)
        $nextAppointment = $this->nextAppointmentService->getNextAppointmentForTeacher($teacher);

        // --- FIX: Count UNIQUE sessions, not individual student appointments ---
        // Group by start_time to count each session only once, regardless of how many students attended
        $totalVerifiedHours = Appointment::where('teacher_id', $teacher->id)
                                         ->where('status', 'verified')
                                         ->select('start_time')
                                         ->distinct()
                                         ->count('start_time');

        $totalPendingHours = Appointment::where('teacher_id', $teacher->id)
                                        ->where('status', 'pending_verification')
                                        ->select('start_time')
                                        ->distinct()
                                        ->count('start_time');
        
        // Weekly slots count (existing logic)
        $hoursThisWeek = WeeklySlot::where('teacher_id', $teacher->id)->count();

        return view('teacher.dashboard', [
            'nextAppointment' => $nextAppointment,
            'totalVerifiedHours' => $totalVerifiedHours,
            'totalPendingHours' => $totalPendingHours,
            'hoursThisWeek' => $hoursThisWeek,
        ]);
    }
}