<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\NextAppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Carbon\Carbon; // <-- ** STEP 1: Import Carbon **
use App\Models\WeeklySlot; //

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
        
// --- ** STEP 2: NEW WEEKLY STATS LOGIC (THE FIX) ** ---
        
        // هذا الكود الجديد يقوم بعدّ إجمالي الحصص الثابتة في الجدول الأسبوعي للمعلم
        // بدلاً من عدّ الحصص المسجلة هذا الأسبوع
        $hoursThisWeek = WeeklySlot::where('teacher_id', $teacher->id)
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