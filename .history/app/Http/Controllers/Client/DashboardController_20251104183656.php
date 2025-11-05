<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
// use App\Services\NextAppointmentService; // <-- ** STEP 1: REMOVE old service **
use App\Models\WeeklySlot; // <-- ** STEP 2: IMPORT new WeeklySlot model **
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <-- ** STEP 3: IMPORT Carbon **

class DashboardController extends Controller
{
    /**
     * We no longer need the NextAppointmentService
     */
    public function __construct()
    {
        // $this->nextAppointmentService = $nextAppointmentService; // <-- REMOVED
    }

    public function index()
    {
        $client = Auth::user();
        
        // --- ** STEP 4: NEW LOGIC TO FIND NEXT LESSON ** ---

        $today = Carbon::now()->dayOfWeek; // 0=Sun, 1=Mon, etc.
        $now = Carbon::now()->format('H:i:s'); // Current time "HH:MM:SS"

        // 1. Try to find the next lesson scheduled for *today*
        $nextLesson = WeeklySlot::where('client_id', $client->id)
            ->with('teacher')
            ->where('day_of_week', $today)
            ->where('start_time', '>', $now)
            ->orderBy('start_time', 'asc')
            ->first();

        // 2. If no lessons are left today, find the next one *this week*
        if (!$nextLesson) {
            $nextLesson = WeeklySlot::where('client_id', $client->id)
                ->with('teacher')
                ->where('day_of_week', '>', $today)
                ->orderBy('day_of_week', 'asc')
                ->orderBy('start_time', 'asc')
                ->first();
        }

        // 3. If no lessons are left this week, find the *first* lesson *next week*
        if (!$nextLesson) {
            $nextLesson = WeeklySlot::where('client_id', $client->id)
                ->with('teacher')
                ->orderBy('day_of_week', 'asc')
                ->orderBy('start_time', 'asc')
                ->first();
        }
        
        // Helper array for day names
        $daysOfWeek = [
            0 => 'الأحد', 1 => 'الاثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 
            4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'
        ];

        // --- ** END OF NEW LOGIC ** ---

        return view('dashboard', [
            'nextLesson' => $nextLesson, // <-- ** STEP 5: Pass new variable **
            'daysOfWeek' => $daysOfWeek,   // <-- Pass day names
        ]);
    }
}
