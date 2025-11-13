<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Subscription; // <-- ** Import Subscription **
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- ** Import DB for transactions **

class SessionVerificationController extends Controller
{
    /**
     * Display a listing of pending sessions for verification.
     */
    public function index()
    {
    // NEW QUERY (eager-load the report AND add pagination):
    $pendingSessions = Appointment::whereIn('status', ['completed', 'conflict', 'verified', 'rejected', 'pending_verification']) // Added pending_verification
        ->with(['teacher', 'client', 'meetReport']) // <-- ADD 'meetReport'
        ->orderBy('start_time', 'desc')
        ->paginate(10); // <-- THIS IS THE PAGINATION (was ->get())

    return view('admin.sessions.index', compact('pendingSessions'));
    }

    /**
     * Verify a completed session.
     * This is the core of your new business logic.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Appointment $appointment)
    {
        // Ensure we are only processing pending sessions
        // We add all non-actionable statuses to this check
        if (in_array($appointment->status, ['verified', 'cancelled'])) {
             return redirect()->back()->withErrors(['message' => 'This session has already been processed.']);
        }

        try {
            // Use a database transaction for safety.
            DB::transaction(function () use ($appointment) {
                
                // ** PROFESSIONAL LOGIC **
                // Only decrement a lesson credit if the session was
                // successfully 'completed' by the teacher or API
                if ($appointment->completion_status === 'completed' || $appointment->status === 'pending_verification') {
                    
                    // Find the client's active subscription
                    $subscription = Subscription::where('user_id', $appointment->client_id)
                        ->where('status', 'active')
                        ->where('ends_at', '>', now())
                        ->whereColumn('lessons_used', '<', 'total_lessons')
                        ->first();
                    
                    // If they have one, increment its 'lessons_used' count
                    if ($subscription) {
                        $subscription->increment('lessons_used');

                        // --- ** THIS IS THE NEW LOGIC (PRIORITY 5) ** ---
                        // After incrementing, check if all lessons have been used.
                        // We refresh the model to get the new 'lessons_used' value.
                        $subscription->refresh();
                        
                        if ($subscription->lessons_used >= $subscription->total_lessons) {
                            $subscription->status = 'expired';
                            $subscription->save(); // Save the new 'expired' status
                        }
                        // --- ** END OF NEW LOGIC ** ---
                    }
                }
                
                // ** ALWAYS **: Mark the appointment as 'verified'
                // This confirms the admin has seen the report,
                // regardless of the outcome.
                $appointment->status = 'verified';
                $appointment->save();

            });

        } catch (\Exception $e) {
            // If anything went wrong, send an error
            return redirect()->back()->withErrors(['message' => 'An error occurred during verification. ' . $e->getMessage()]);
        }

        // If successful, send a success message
        return redirect()->route('admin.sessions.verify.index')->with('status', 'تمت مراجعة الحصة بنجاح.');
    }
}