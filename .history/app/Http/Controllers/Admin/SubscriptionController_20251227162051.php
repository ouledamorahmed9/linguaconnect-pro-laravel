<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Show the form for creating a new subscription for a specific client.
     */
    public function create(User $client): View
    {
        // NEW: Load plans from the central config file
        $plans = config('plans');

        return view('admin.subscriptions.create', compact('client', 'plans'));
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request, User $client)
    {
        // 1. Load Plans
        $plans = config('plans');
        $availableKeys = implode(',', array_keys($plans)); // e.g., "normal,vip,duo,private"

        // 2. Validate against the Config Keys
        $validated = $request->validate([
            'plan_type' => ['required', 'string', 'in:' . $availableKeys],
            'starts_at' => ['required', 'date'],
        ]);

        $planKey = $validated['plan_type'];
        $planData = $plans[$planKey];

        // 3. Calculate Dates
        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = $startsAt->copy()->addMonth(); // Standard 30-day cycle

        // Optional: Check if user already has an active subscription to prevent overlaps
        if ($client->subscription && $client->subscription->isActive()) {
            return back()->with('error', 'This client already has an active subscription.');
        }

        // 4. Create Subscription
        // Note: We use 'type' instead of 'plan_type' to match the new database schema
        $subscription = $client->subscriptions()->create([
            'type'          => $planKey, 
            'total_lessons' => $planData['lessons_count'], 
            'lessons_used'  => 0, 
            'price'         => $planData['price'], // Saving price for history
            'currency'      => '$',
            'starts_at'     => $startsAt,
            'ends_at'       => $endsAt,
            'status'        => 'active',
        ]);

        // 5. Log Activity (Preserved from your original code)
        if (function_exists('activity')) {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($subscription)
                ->log("Assigned a new '{$planData['name']}' subscription to client '{$client->name}'");
        }

        return redirect()->route('admin.clients.edit', $client)
            ->with('status', 'New subscription has been assigned successfully!');
    }

    /**
     * Remove the specified subscription from storage.
     */
    public function destroy(Subscription $subscription)
    {
        // Get the client ID before deleting, so we can redirect back
        $clientId = $subscription->user_id;

        // Delete the subscription
        $subscription->delete();

        // Redirect back to the client's edit page with a success message
        return redirect()->route('admin.clients.edit', $clientId)
                                 ->with('status', 'Subscription cancelled successfully.');
    }
}