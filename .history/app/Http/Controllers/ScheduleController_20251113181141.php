<?php

namespace App\Http\Controllers; // We keep the original namespace as requested

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WeeklySlot;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display the client's weekly schedule (roster).
     */
    public function index()
    {
        $client = Auth::user();

        // --- ** THIS IS THE FIX ** ---
        // Get all weekly slots for THIS client, grouped by day
        $weeklySlots = WeeklySlot::where('client_id', $client->id)
            ->with('teacher') // Load the teacher's info
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_of_week');

        // Helper array for day names
        $daysOfWeek = [
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            0 => 'الأحد',
        ];

        return view('schedule.index', [
            'weeklySlots' => $weeklySlots,
            'daysOfWeek' => $daysOfWeek,
        ]);
    }
}