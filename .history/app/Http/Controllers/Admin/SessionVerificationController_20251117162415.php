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
        // --- THIS IS THE FIX ---
        // 1. We only get sessions that are 'pending_verification'.
        // 2. We load the client and teacher data for the table.
        $pendingSessions = Appointment::where('status', 'pending_verification')
                                      ->with('client.coordinator', 'teacher') // ** هذا هو التعديل **
                                      ->orderBy('start_time', 'desc')
                                      ->paginate(15);
        
        // 3. We pass the data to the view.
        return view('admin.sessions.index', [
            'sessions' => $pendingSessions,
        ]);
        // --- END OF FIX ---
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
        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'This session has already been processed.']);
        }

        try {
            // Use a database transaction for safety.
            DB::transaction(function () use ($appointment) {
                
                // ** PROFESSIONAL LOGIC **
                // Only decrement a lesson credit if the session was
                // successfully 'completed' by the teacher.
                if ($appointment->completion_status === 'completed') {
                    
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
    
    public function cancel(Request $request, Appointment $appointment)
    {
        // 1. التأكد أن الحصة بانتظار المراجعة
        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'تمت معالجة هذه الحصة مسبقاً.']);
        }

        try {
            DB::transaction(function () use ($appointment) {
                // 2. تغيير الحالة إلى "ملغاة" مباشرة
                $appointment->status = 'cancelled';
                $appointment->save();
                // (لا يتم إنشاء أي نزاع)
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'حدث خطأ. ' . $e->getMessage()]);
        }

        // 3. إرجاع رسالة نجاح
        return redirect()->route('admin.sessions.verify.index')->with('status', 'تم إلغاء الحصة بنجاح.');
    }
    // --- ** انتهت الإضافة ** ---
}