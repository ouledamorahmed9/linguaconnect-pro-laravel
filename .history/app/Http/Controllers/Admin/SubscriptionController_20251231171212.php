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
        return view('admin.subscriptions.create', compact('client'));
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request, User $client)
    {
        $validated = $request->validate([
            'plan_type' => ['required', 'string', 'in:basic,advanced,intensive'],
            'starts_at' => ['required', 'date'],
        ]);

        // THIS IS THE FIX: We define the business logic for our plans.
        $lessonCredits = [
            'basic' => 4,
            'advanced' => 8,
            'intensive' => 12,
        ];

        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = $startsAt->copy()->addMonth();

        // The model will automatically log the 'created' event.
        $subscription = $client->subscriptions()->create([
            'plan_type' => $validated['plan_type'],
            'total_lessons' => $lessonCredits[$validated['plan_type']], // We now send the correct number of lessons.
            'lessons_used' => 0, // We initialize the used lessons to zero.
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($subscription)
            ->log("Assigned a new '{$subscription->plan_type}' subscription to client '{$client->name}'");

        return redirect()->route('admin.clients.edit', $client)->with('status', 'New subscription has been assigned successfully!');
    }

    /**
     * Remove the specified subscription from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\RedirectResponse
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