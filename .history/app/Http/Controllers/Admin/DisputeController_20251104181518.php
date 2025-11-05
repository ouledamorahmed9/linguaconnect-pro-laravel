<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Appointment; // <-- ** Import Appointment **
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- ** Import Auth **
use Illuminate\Support\Facades\DB;   // <-- ** Import DB for transaction **

class DisputeController extends Controller
{
    /**
     * Display a listing of all open disputes.
     */
    public function index()
    {
        // This existing logic is correct.
        $disputes = Dispute::where('status', 'open')
                           ->with('appointment.client', 'appointment.teacher')
                           ->orderBy('created_at', 'desc')
                           ->paginate(15);
                           
        return view('admin.disputes.index', [
            'disputes' => $disputes,
        ]);
    }

    /**
     * Store a new dispute (refuse a session)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Appointment $appointment)
    {
        // 1. Ensure we are only processing pending sessions
        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'This session has already been processed.']);
        }

        // 2. We will add a reason field later. For now, use a default.
        $reason = $request->input('reason', 'Admin refused this session.');

        try {
            // Use a database transaction for safety.
            DB::transaction(function () use ($appointment, $reason) {
                
                // 3. Create the new Dispute record
                Dispute::create([
                    'appointment_id' => $appointment->id,
                    'admin_id' => Auth::id(),
                    'reason' => $reason,
                    'status' => 'open', // Default status for a new dispute
                ]);

                // 4. Change the Appointment status to 'disputed'
                $appointment->status = 'disputed';
                $appointment->save();

                // 5. CRUCIAL: We do NOT increment the subscription.
                // The lesson credit is not used.
            });

        } catch (\Exception $e) {
            // If anything went wrong, send an error
            return redirect()->back()->withErrors(['message' => 'An error occurred while creating the dispute. ' . $e->getMessage()]);
        }

        // 6. If successful, send a success message
        return redirect()->route('admin.sessions.verify.index')
                         ->with('status', 'تم رفض الحصة وإرسالها إلى صفحة النزاعات.');
    }

    /**
     * Resolve a dispute.
     * (This logic is not part of our current step, but is here for completeness)
     */
    public function resolve(Dispute $dispute)
    {
        // Logic to resolve a dispute (e.g., mark it 'resolved')
        // $dispute->status = 'resolved';
        // $dispute->save();
        // return redirect()->back()->with('status', 'Dispute resolved.');
    }

    /**
     * Cancel a dispute.
     * (This logic is not part of our current step)
     */
    public function cancel(Dispute $dispute)
    {
        // Logic to cancel a dispute (e.g., re-open the session for verification)
        // $dispute->delete();
        // $dispute->appointment->update(['status' => 'pending_verification']);
        // return redirect()->back()->with('status', 'Dispute cancelled.');
    }
}
