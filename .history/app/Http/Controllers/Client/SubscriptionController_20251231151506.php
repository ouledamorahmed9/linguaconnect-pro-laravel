<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = config('plans');
        $currentSubscription = Subscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->latest()
            ->first();

        return view('client.subscription.index', compact('plans', 'currentSubscription'));
    }

    public function create()
    {
        return view('client.subscription.create');
    }

    public function store(Request $request)
    {
        // 1. Get valid plan keys dynamically from config (normal, vip, duo, private)
        $plans = config('plans');
        $validPlanKeys = array_keys($plans);

        // 2. Validate using the dynamic list
        $validated = $request->validate([
            'plan' => ['required', 'string', Rule::in($validPlanKeys)],
        ]);

        $planKey = $validated['plan'];
        $selectedPlan = $plans[$planKey];

        // 3. Create the Subscription record
        $subscription = Subscription::create([
            'user_id' => Auth::id(),
            'name' => $selectedPlan['name'],
            'price' => $selectedPlan['price'],
            'credits' => $selectedPlan['lessons_count'], // Getting credit amount from config
            'status' => 'active', // Assuming immediate activation for now, change if you have payment logic
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ]);

        // 4. Add Credits to User (The logic you wanted to keep)
        // This links perfectly with SessionVerificationController logic later
        $user = Auth::user();
        $user->credits += $selectedPlan['lessons_count'];
        $user->save();

        return redirect()->route('client.subscription.index')
            ->with('success', 'Subscription activated successfully! Credits added.');
    }
}