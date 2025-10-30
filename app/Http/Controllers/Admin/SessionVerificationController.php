<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Subscription; // Import the Subscription model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // Import RedirectResponse

class SessionVerificationController extends Controller
{
    /**
     * Display a listing of all sessions pending verification.
     */
    public function index(): View
    {
        $pendingSessions = Appointment::with(['teacher', 'client'])
            ->where('status', 'logged')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('admin.sessions.index', compact('pendingSessions'));
    }

    /**
     * Verify a logged session, mark it as 'verified', and decrement client's lesson credits.
     */
    public function verify(Appointment $appointment): RedirectResponse
    {
        // 1. Find the client's active subscription that covers this appointment's date.
        $activeSubscription = Subscription::where('user_id', $appointment->client_id)
            ->where('status', 'active')
            ->where('starts_at', '<=', $appointment->start_time)
            ->where('ends_at', '>=', $appointment->start_time)
            ->first();

        // 2. Check for potential issues
        if (!$activeSubscription) {
            return redirect()->back()->with('error', 'Verification failed: No active subscription found for this client on this date.');
        }

        if ($activeSubscription->lessons_used >= $activeSubscription->total_lessons) {
            return redirect()->back()->with('error', 'Verification failed: Client has no remaining lesson credits.');
        }

        // 3. All checks passed. Proceed with the professional "transaction".
        // Increment the 'lessons_used' count on the subscription
        $activeSubscription->increment('lessons_used');
        
        // Update the appointment status to 'verified'
        $appointment->update(['status' => 'verified']);

        // 4. Log this critical admin action
        activity()
            ->causedBy(Auth::user())
            ->performedOn($appointment)
            ->log("Verified session for client '{$appointment->client->name}' with teacher '{$appointment->teacher->name}'");

        return redirect()->back()->with('status', 'Session verified and lesson credit used successfully!');
    }
}

