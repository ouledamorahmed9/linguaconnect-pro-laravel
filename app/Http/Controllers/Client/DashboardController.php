<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\WeeklySlot;
use App\Models\Subscription; // <-- ** 1. ADD THIS **
use App\Models\Appointment;  // <-- ** 2. ADD THIS **
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $client = Auth::user();

        // --- Logic to find NEXT lesson (this is your existing, correct logic) ---

        $today = Carbon::now()->dayOfWeek; // 0=Sun, 1=Mon, etc.
        $now = Carbon::now()->format('H:i:s'); // Current time "HH:MM:SS"

        $nextLesson = WeeklySlot::where('client_id', $client->id)
            ->with('teacher')
            ->where('day_of_week', $today)
            ->where('start_time', '>', $now)
            ->orderBy('start_time', 'asc')
            ->first();

        if (!$nextLesson) {
            $nextLesson = WeeklySlot::where('client_id', $client->id)
                ->with('teacher')
                ->where('day_of_week', '>', $today)
                ->orderBy('day_of_week', 'asc')
                ->orderBy('start_time', 'asc')
                ->first();
        }

        if (!$nextLesson) {
            $nextLesson = WeeklySlot::where('client_id', $client->id)
                ->with('teacher')
                ->orderBy('day_of_week', 'asc')
                ->orderBy('start_time', 'asc')
                ->first();
        }

        $daysOfWeek = [
            0 => 'الأحد',
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت'
        ];

        // --- ** 3. NEW LOGIC: Get Subscription & Reports ** ---

        // Get the client's active subscriptions
        $activeSubscriptions = Subscription::where('user_id', $client->id)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest('starts_at')
            ->get();

        // Get the 3 most recent lesson reports
        $latestReports = Appointment::where('client_id', $client->id)
            ->whereIn('status', ['verified', 'disputed', 'cancelled'])
            ->with('teacher')
            ->orderBy('start_time', 'desc')
            ->limit(3)
            ->get();

        // --- ** END OF NEW LOGIC ** ---


        return view('dashboard', [
            'nextLesson' => $nextLesson,
            'daysOfWeek' => $daysOfWeek,
            'activeSubscriptions' => $activeSubscriptions, // <-- 4. Pass new data
            'latestReports' => $latestReports,           // <-- 4. Pass new data
        ]);
    }
}