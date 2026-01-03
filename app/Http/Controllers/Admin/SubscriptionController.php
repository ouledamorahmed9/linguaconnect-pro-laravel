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
        $studySubjects = \App\Models\StudySubject::where('is_active', true)->get();
        return view('admin.subscriptions.create', compact('client', 'studySubjects'));
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request, User $client)
    {
        $validated = $request->validate([
            'plan_type' => ['required', 'string', 'in:one_to_one,duo,vip,normal,free_trial'],
            'target_language' => ['required', 'string'],
            'starts_at' => ['required', 'date'],
        ]);

        // Check for existing active subscription for this language
        $existingSub = $client->subscriptions()
            ->where('target_language', $validated['target_language'])
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();

        // Allow multiple free trials? Maybe better to block validation if needed, 
        // but for now, admin might want to override.
        if ($existingSub) {
            return back()->withErrors(['target_language' => 'This client already has an active subscription for ' . $validated['target_language']]);
        }

        // THIS IS THE FIX: We define the business logic for our plans.
        // All plans now have 8 sessions by default.
        $lessonCredits = [
            'one_to_one' => 8,
            'duo' => 8,
            'vip' => 8,
            'normal' => 8,
            'free_trial' => 1, // Free trial functionality
        ];

        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = $startsAt->copy()->addMonth();

        // The model will automatically log the 'created' event.
        $subscription = $client->subscriptions()->create([
            'plan_type' => $validated['plan_type'],
            'target_language' => $validated['target_language'],
            'total_lessons' => $lessonCredits[$validated['plan_type']], // We now send the correct number of lessons.
            'lessons_used' => 0, // We initialize the used lessons to zero.
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($subscription)
            ->log("Assigned a new '{$subscription->plan_type}' subscription ({$subscription->target_language}) to client '{$client->name}'");

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

    /**
     * Display a list of new subscription requests.
     */
    public function newRequests()
    {
        $subscriptions = Subscription::with([
            'user' => function ($query) {
                // Eager load only future appointments for the user
                $query->with([
                    'appointments' => function ($q) {
                    $q->where('start_time', '>', now());
                }
                ]);
            }
        ])
            ->whereNotNull('target_language')
            ->latest()
            ->paginate(20);

        return view('admin.subscriptions.new_requests', compact('subscriptions'));
    }
}