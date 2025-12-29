<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription; 

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Get the Active Subscription
        // We look for a subscription that is 'active'
        $activeSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->latest()
            ->first();

        // 2. Get Next Lesson (Safe check)
        $nextLesson = null;
        if (method_exists($user, 'appointments')) {
            $nextLesson = $user->appointments()
                ->where('start_time', '>', now())
                ->where('status', 'confirmed')
                ->orderBy('start_time', 'asc')
                ->first();
        }

        return view('dashboard', compact('user', 'activeSubscription', 'nextLesson'));
    }
}