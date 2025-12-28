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

            // Find the current active subscription
            $activeSubscription = Subscription::where('user_id', $clientId)
                ->where('status', 'active')
                ->where('ends_at', '>', now())
                ->latest('starts_at')
                ->first();

            // Find all expired or cancelled subscriptions for their history
            $pastSubscriptions = Subscription::where('user_id', $clientId)
                ->where('status', '!=', 'active')
                ->orWhere('ends_at', '<=', now())
                ->latest('ends_at')
                ->get();

            return view('client.subscription.index', compact('activeSubscription', 'pastSubscriptions'));
        }
    }
    
