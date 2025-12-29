<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function create($planKey)
    {
        $plans = config('plans');

        if (!array_key_exists($planKey, $plans)) {
            abort(404);
        }

        $plan = $plans[$planKey];
        
        return view('client.subscription.create', compact('plan', 'planKey'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_key' => 'required|string',
        ]);

        $planKey = $request->input('plan_key');
        $plans = config('plans');

        if (!isset($plans[$planKey])) {
            return back()->with('error', 'Invalid plan selected.');
        }

        $selectedPlan = $plans[$planKey];

        // --- THE FINAL FIX IS HERE ---
        Subscription::create([
            'user_id'        => Auth::id(),
            'plan_type'      => $planKey,
            'type'           => $planKey,
            'price'          => $selectedPlan['price'],
            'currency'       => 'USD',
            'payment_status' => 'pending', 
            'status'         => 'active',
            'start_date'     => Carbon::now(),
            'end_date'       => Carbon::now()->addMonth(),
            
            // Saving the lesson counts from config
            'total_lessons'  => $selectedPlan['lessons_count'],  // e.g., 8
            'lesson_credits' => $selectedPlan['lessons_count'],  // Starts with 8 credits
        ]);

        return redirect()->route('client.dashboard')->with('success', 'تم الاشتراك بنجاح! رصيدك الآن ' . $selectedPlan['lessons_count'] . ' حصص.');
    }
}