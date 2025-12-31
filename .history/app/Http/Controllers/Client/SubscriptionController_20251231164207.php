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
        $subscription = Subscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->latest()
            ->first();

        return view('client.subscription.index', compact('subscription'));
    }
}