<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        // FIX: Changed subscription() to subscriptions()
        $subscription = auth()->user()->subscriptions()
                        ->where('status', 'active')
                        ->latest()
                        ->first();
                        
        $plans = config('plans');

        return view('client.subscription.index', compact('subscription', 'plans'));
    }

    public function create($planKey)
    {
        $plans = config('plans');

        if (!array_key_exists($planKey, $plans)) {
            abort(404);
        }

        $plan = $plans[$planKey];
        $plan['key'] = $planKey;

        return view('client.subscription.create', compact('plan'));
    }

    public function store(Request $request)
    {
        $request->validate(['plan' => 'required|string']);
        
        $plans = config('plans');
        $planKey = $request->plan;

        if (!array_key_exists($planKey, $plans)) {
            return back()->with('error', 'الباقة غير موجودة.');
        }

        $planData = $plans[$planKey];
        $user = auth()->user();

        // FIX: Changed subscription() to subscriptions()
        $existingSub = $user->subscriptions()->where('status', 'active')->first();
        
        if ($existingSub) {
            return back()->with('error', 'لديك اشتراك نشط بالفعل. يرجى الانتظار حتى انتهائه.');
        }

        // Create Subscription
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
        return redirect()->route('client.subscription.index')
            ->with('status', 'تم الاشتراك بنجاح! شكراً لانضمامك إلينا.');
    }
}