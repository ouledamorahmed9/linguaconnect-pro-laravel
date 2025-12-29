<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Show the subscription creation page.
     */
    public function create($planKey)
    {
        $plans = config('plans');

        if (!array_key_exists($planKey, $plans)) {
            abort(404);
        }

        $plan = $plans[$planKey];
        
        return view('client.subscription.create', compact('plan', 'planKey'));
    }

    /**
     * Store the new subscription in the database.
     */
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

        // --- THE FIX IS HERE ---
        Subscription::create([
            'user_id'        => Auth::id(),
            'plan_type'      => $planKey,             // <--- Satisfies the 'plan_type' error
            'type'           => $planKey,             // Fills the 'type' column you added
            'price'          => $selectedPlan['price'],
            'currency'       => 'USD',                // Fills the 'currency' column
            'payment_status' => 'pending',            // Fills the 'payment_status' column
            'status'         => 'active',
            'start_date'     => Carbon::now(),
            'end_date'       => Carbon::now()->addMonth(),
        ]);

        return redirect()->route('client.dashboard')->with('success', 'تم الاشتراك بنجاح! أهلاً بك في أكاديمية كمون.');
    }
}