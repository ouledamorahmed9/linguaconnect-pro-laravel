<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function create(User $client)
    {
        $plans = config('plans');
        return view('admin.subscriptions.create', compact('client', 'plans'));
    }

    public function store(Request $request, User $client)
    {
        // 1. Get valid plans from config
        $plans = config('plans');
        $validKeys = array_keys($plans);

        // 2. Validate input
        $request->validate([
            'plan' => ['required', Rule::in($validKeys)],
        ]);

        // 3. Get Plan Details
        $planKey = $request->plan;
        $planDetails = $plans[$planKey];

        // 4. Create Subscription
        // Ensure 'plan_type' is included to fix Error 1364
        Subscription::create([
            'user_id' => $client->id,
            'plan_type' => $planKey,  // <--- KEY FIX: explicitly saving the plan key
            'name' => $planDetails['name'],
            'price' => $planDetails['price'],
            'credits' => $planDetails['lessons_count'], // Getting credits from config 'lessons_count'
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ]);

        // 5. Add credits to the User
        $client->credits += $planDetails['lessons_count'];
        $client->save();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Subscription assigned successfully!');
    }
}