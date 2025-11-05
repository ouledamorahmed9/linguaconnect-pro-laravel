<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import Auth
use App\Models\WeeklySlot; // <-- ** STEP 1: Import the new WeeklySlot model **

class ScheduleController extends Controller
{
    /**
     * Display the client's new weekly schedule (roster).
     */
    public function index()
    {
        // --- ** STEP 2: THIS IS THE NEW LOGIC ** ---
        $client = Auth::user();

        // Get all weekly slots for this client, ordered by day and time
        $weeklySlots = WeeklySlot::where('client_id', $client->id)
            ->with('teacher') // Eager load the teacher's name
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

        return view('schedule.index', [
            'weeklySlots' => $weeklySlots,
            'daysOfWeek' => $daysOfWeek,
        ]);
    }
}
