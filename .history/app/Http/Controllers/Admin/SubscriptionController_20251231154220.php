<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client; // Or User, depending on your setup
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function create(User $client)
    {
        // Pass the new dynamic plans to the view
        $plans = config('plans');
        return view('admin.subscriptions.create', compact('client', 'plans'));
    }

    public function store(Request $request, User $client)
    {
        // 1. Validate against the config keys (normal, vip, duo, private)
        $plans = config('plans');
        $validKeys = array_keys($plans);

        $request->validate([
            'plan' => ['required', Rule::in($validKeys)],
        ]);

        $selectedPlanKey = $request->plan;
        $planDetails = $plans[$selectedPlanKey];

        // 2. Create the Subscription
        // FIX: We explicitly map the form input 'plan' to the database column 'plan_type'
        Subscription::create([
            'user_id' => $client->id,
            'plan_type' => $selectedPlanKey, // <--- THIS FIXES ERROR 1364
            'name' => $planDetails['name'],
            'price' => $planDetails['price'],
            'credits' => $planDetails['lessons_count'], // Get credits from config
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ]);

        // 3. Add Credits to the User
        $client->credits += $planDetails['lessons_count'];
        $client->save();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Subscription assigned successfully!');
    }
}