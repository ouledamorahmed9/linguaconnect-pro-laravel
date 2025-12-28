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
        Subscription::create([
            'user_id'       => $user->id,
            'type'          => $planKey,
            'status'        => 'active',
            'total_lessons' => $planData['lessons_count'],
            'lessons_used'  => 0,
            'price'         => $planData['price'],
            'currency'      => '$',
            'starts_at'     => Carbon::now(),
            'ends_at'       => Carbon::now()->addMonth(), // Standard 30 days
        ]);

        return redirect()->route('client.subscription.index')
            ->with('status', 'تم الاشتراك بنجاح! شكراً لانضمامك إلينا.');
    }
}