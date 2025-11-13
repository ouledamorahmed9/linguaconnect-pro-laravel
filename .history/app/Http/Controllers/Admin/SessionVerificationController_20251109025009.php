<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\SessionVerificationService;
use Illuminate\Http\Request;
use App\Models\MeetReport; // We need this for the `with`

class SessionVerificationController extends Controller
{

    /**
     * Display a list of sessions needing verification or review.
     */
    public function index()
    {
        // We now get all actionable statuses and paginate the results
        $appointments = Appointment::with(['client', 'teacher', 'meetReport'])
            ->whereIn('status', [
                'pending_verification', 
                'conflict',
                'verified', // So admin can see auto-verified
                'cancelled' // So admin can see auto-cancelled
            ])
            ->orderBy('start_time', 'desc') // Show newest first
            ->paginate(10); // <-- THIS IS THE PAGINATION

        return view('admin.sessions.index', compact('appointments'));
    }

    /**
     * Manually verify a session.
     */
    public function verify(Appointment $appointment)
    {
        $this->verificationService->verifySession($appointment);
        return back()->with('success', 'Session marked as verified.');
    }

    /**
     * Manually create a dispute (reject) a session.
     */
    public function store(Request $request, Appointment $appointment)
    {
        // This logic remains the same
        // ... (your existing dispute logic)
        return back()->with('error', 'Session rejected and dispute created.');
    }
    
        protected $verificationService;

    public function __construct(SessionVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

}