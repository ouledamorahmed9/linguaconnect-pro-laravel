<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function create()
    {
        $clients = User::where('role', 'client')->get();
        return view('admin.subscriptions.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:monthly,yearly',
            'start_date' => 'required|date',
            'lesson_credits' => 'required|integer|min:0', // Validate lesson credits
        ]);

        $endDate = $request->type === 'monthly'
            ? \Carbon\Carbon::parse($request->start_date)->addMonth()
            : \Carbon\Carbon::parse($request->start_date)->addYear();

        Subscription::create([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $endDate,
            'status' => 'active',
            'payment_status' => 'paid', // Assuming manual creation implies payment
            'lesson_credits' => $validated['lesson_credits'], // Save lesson credits
        ]);

        return redirect()->route('admin.clients.index')->with('success', 'Subscription created successfully.');
    }
}