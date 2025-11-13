<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionVerificationController extends Controller
{
    /**
     * Display a listing of pending sessions for verification.
     */
    public function index()
    {
        // --- THIS IS THE FIX ---
        // 1. We ONLY get sessions that are 'pending_verification'.
        // 2. We load the client and teacher data for the table.
        // 3. We paginate the results.
        $pendingSessions = Appointment::where('status', 'pending_verification')
                                      ->with('client', 'teacher')
                                      ->orderBy('start_time', 'desc')
                                      ->paginate(15);
        
        // 3. We pass the data to the view.
        return view('admin.sessions.index', [
            'pendingSessions' => $pendingSessions,
        ]);
        // --- END OF FIX ---
    }

    /**
     * Verify a completed session.
     * (This logic is correct and remains unchanged)
     */
    public function verify(Appointment $appointment)
    {
        // Ensure we are only processing pending sessions
        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'This session has already been processed.']);
        }

        try {
            // Use a database transaction for safety.
            DB::transaction(function () use ($appointment) {
                
                if ($appointment->completion_status === 'completed') {
                    
                    // Find the client's active subscription
                    $subscription = Subscription::where('user_id', $appointment->client_id)
                        ->where('status', 'active')
                        ->where('ends_at', '>', now())
                        ->whereColumn('lessons_used', '<', 'total_lessons')
                        ->first();
                    
                    if ($subscription) {
                        $subscription->increment('lessons_used');
                        $subscription->refresh();
                        
                        if ($subscription->lessons_used >= $subscription->total_lessons) {
                            $subscription->status = 'expired';
                            $subscription->save();
                        }
                    }
                }
                
                $appointment->status = 'verified';
                $appointment->save();

            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'An error occurred during verification. ' . $e->getMessage()]);
        }

        return redirect()->route('admin.sessions.verify.index')->with('status', 'تمت مراجعة الحصة بنجاح.');
    }
}