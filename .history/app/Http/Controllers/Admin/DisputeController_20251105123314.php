<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App.Models\Appointment;
use App.Models\Subscription; // <-- ** STEP 1: Import Subscription **
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;   // <-- ** STEP 2: Import DB for transaction **

class DisputeController extends Controller
{
    /**
     * Display a listing of all open disputes.
     */
    public function index()
    {
        $disputes = Dispute::where('status', 'open')
                           ->with('appointment.client', 'appointment.teacher') // Eager load relationships
                           ->orderBy('created_at', 'desc')
                           ->paginate(15);
                           
        return view('admin.disputes.index', [
            'disputes' => $disputes,
        ]);
    }

    /**
     * Store a new dispute (refuse a session)
     * (This existing logic is correct)
     */
    public function store(Request $request, Appointment $appointment)
    {
        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'This session has already been processed.']);
        }

        $reason = $request->input('reason', 'Admin refused this session.');

        try {
            DB::transaction(function () use ($appointment, $reason) {
                Dispute::create([
                    'appointment_id' => $appointment->id,
                    'admin_id' => Auth::id(),
                    'reason' => $reason,
                    'status' => 'open',
                ]);

                $appointment->status = 'disputed';
                $appointment->save();
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'An error occurred while creating the dispute. ' . $e->getMessage()]);
        }

        return redirect()->route('admin.sessions.verify.index')
                         ->with('status', 'تم رفض الحصة وإرسالها إلى صفحة النزاعات.');
    }

    /**
     * ---
     * **NEW LOGIC: RESOLVE A DISPUTE (APPROVE THE LESSON)**
     * ---
     * This marks the lesson as 'verified' and uses a client credit.
     *
     * @param  \App\Models\Dispute  $dispute
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resolve(Dispute $dispute)
    {
        // 1. Ensure dispute is open
        if ($dispute->status !== 'open') {
            return redirect()->back()->withErrors(['message' => 'This dispute has already been processed.']);
        }

        try {
            DB::transaction(function () use ($dispute) {
                $appointment = $dispute->appointment;

                // 2. **Check lesson type**. Only charge for 'completed' lessons.
                if ($appointment->completion_status === 'completed') {
                    
                    // 3. Find and increment the client's subscription
                    $subscription = Subscription::where('user_id', $appointment->client_id)
                        ->where('status', 'active')
                        ->where('ends_at', '>', now())
                        ->whereColumn('lessons_used', '<', 'total_lessons')
                        ->first();
                    
                    if ($subscription) {
                        $subscription->increment('lessons_used');
                    }
                }
                
                // 4. Update the records
                $appointment->status = 'verified'; // Mark lesson as approved
                $appointment->save();

                $dispute->status = 'resolved'; // Mark dispute as closed
                $dispute->save();
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'An error occurred. ' . $e->getMessage()]);
        }

        return redirect()->route('admin.disputes.index')->with('status', 'تم حل النزاع واعتماد الحصة.');
    }

    /**
     * ---
     * **NEW LOGIC: CANCEL A DISPUTE (CONFIRM REFUSAL)**
     * ---
     * This marks the lesson as 'cancelled' and does NOT use a credit.
     *
     * @param  \App\Models\Dispute  $dispute
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Dispute $dispute)
    {
        // 1. Ensure dispute is open
        if ($dispute->status !== 'open') {
            return redirect()->back()->withErrors(['message' => 'This dispute has already been processed.']);
        }
        
        try {
            DB::transaction(function () use ($dispute) {
                // 2. Get the appointment and mark it as 'cancelled'
                $appointment = $dispute->appointment;
                $appointment->status = 'cancelled'; // Or 'refused', but 'cancelled' is clearer
                $appointment->save();

                // 3. Mark the dispute as resolved (i.e., closed)
                $dispute->status = 'resolved';
                $dispute->save();

                // 4. No subscription logic is needed, as the lesson was not approved.
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'An error occurred. ' . $e->getMessage()]);
        }

        return redirect()->route('admin.disputes.index')->with('status', 'تم حل النزاع وإلغاء الحصة.');
    }
}