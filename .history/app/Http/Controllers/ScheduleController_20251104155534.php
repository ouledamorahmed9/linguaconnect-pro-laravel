<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth
use App\Models\WeeklySlot; // <-- ** STEP 1: Import the new WeeklySlot model **

class ScheduleController extends Controller
{
    /**
     * Display the teacher's new weekly schedule (roster).
     */
    public function index()
    {
        // --- ** STEP 2: THIS IS THE NEW LOGIC ** ---
        $teacher = Auth::user();

        // Get all weekly slots for this teacher, ordered by day and time
        $weeklySlots = WeeklySlot::where('teacher_id', $teacher->id)
            ->with('client') // Eager load the client's name
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_of_week'); // Group the slots by the day of the week

        // Helper array to display day names in order
        $daysOfWeek = [
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            0 => 'الأحد',
        ];
        // --- ** END OF NEW LOGIC ** ---

        return view('teacher.schedule.index', [
            'weeklySlots' => $weeklySlots,
            'daysOfWeek' => $daysOfWeek,
        ]);
    }
}

