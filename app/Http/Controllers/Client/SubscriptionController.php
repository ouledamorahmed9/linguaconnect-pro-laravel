<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Display the client's subscription management page.
     */
    public function index(): View
    {
        $clientId = Auth::id();

        // Find all active subscriptions
        $activeSubscriptions = Subscription::where('user_id', $clientId)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest('starts_at')
            ->get();

        // Find all expired or cancelled subscriptions for their history
        $pastSubscriptions = Subscription::where('user_id', $clientId)
            ->where('status', '!=', 'active')
            ->orWhere('ends_at', '<=', now())
            ->latest('ends_at')
            ->get();

        return view('client.subscription.index', compact('activeSubscriptions', 'pastSubscriptions'));
    }

    public function create($plan)
    {
        // Get plans securely from PricingController
        $plans = \App\Http\Controllers\PricingController::getPlans();

        if (!array_key_exists($plan, $plans)) {
            abort(404, 'Plan not found');
        }

        $selectedPlan = $plans[$plan];

        $studySubjects = \App\Models\StudySubject::where('is_active', true)->get();

        return view('client.subscription.checkout', [
            'plan' => $selectedPlan,
            'planKey' => $plan,
            'currency' => 'د.ت',
            'studySubjects' => $studySubjects
        ]);
    }

    public function store(Request $request)
    {
        $plans = \App\Http\Controllers\PricingController::getPlans();

        $request->validate([
            'plan' => 'required|string|in:' . implode(',', array_keys($plans)),
            'target_language' => 'required|string',
        ]);

        // Check if user already has an active subscription for this language
        $existingSub = Subscription::where('user_id', Auth::id())
            ->where('target_language', $request->input('target_language'))
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();

        if ($existingSub) {
            return back()->withErrors(['target_language' => 'لديك بالفعل اشتراك نشط في هذه اللغة.']);
        }

        $planKey = $request->input('plan');
        $selectedPlan = $plans[$planKey];

        // Create the subscription
        // Logic: 7-Day Free Trial means we give them access now, but maybe set a special status or just active?
        // For now, to "complete the steps", we make it active immediately.

        $subscription = Subscription::create([
            'user_id' => Auth::id(),
            'plan_type' => $planKey,
            'target_language' => $request->input('target_language'),
            'starts_at' => now(),
            'ends_at' => now()->addMonth(), // Or addDays(7) if it's strictly a trial that expires? User said "discount 0 dollar", implies full access. Let's give a month for safety/demo.
            'status' => 'active',
            'total_lessons' => $selectedPlan['lessons_count'],
            'lessons_used' => 0,
            // 'notes' => '7-Day Free Trial Activated' // If we had a notes column
        ]);

        return redirect()->route('client.subscription.success', ['subscription' => $subscription->id]);
    }

    public function success(Subscription $subscription)
    {
        // Ensure user owns this subscription
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        return view('client.subscription.success', compact('subscription'));
    }

    public function updateWhatsapp(Request $request, Subscription $subscription)
    {
        // Ensure user owns this subscription
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'whatsapp_number' => 'required|string|max:20',
        ]);

        $subscription->update([
            'whatsapp_number' => $request->whatsapp_number
        ]);

        return redirect()->route('client.dashboard')->with('success', 'تم استلام بياناتك بنجاح! سنتواصل معك قريباً.');
    }
}
