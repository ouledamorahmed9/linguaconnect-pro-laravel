<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Dispute;
use App\Services\SessionVerificationService; // Import our new service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DisputeController extends Controller
{
    protected $verificationService;

    // We also inject the service here
    public function __construct(SessionVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * Display a listing of all open disputes.
     * (This logic is merged from DisputeManagementController)
     */
    public function index(): View
    {
        $openDisputes = Dispute::with(['appointment.teacher', 'appointment.client', 'admin'])
            ->where('status', 'open')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.disputes.index', compact('openDisputes'));
    }

    /**
     * Store a new dispute for a logged session.
     */
    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        // Use firstOrCreate to avoid duplicate disputes for the same session
        $dispute = $appointment->disputes()->firstOrCreate(
            [
                'admin_id' => Auth::id(),
                'status' => 'open',
            ],
            [
                'reason' => $validated['reason'],
            ]
        );

        $appointment->update(['status' => 'disputed']);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($appointment)
            ->log("Raised a dispute for session with reason: {$validated['reason']}");

        return redirect()->route('admin.sessions.verify.index')->with('status', 'Session has been disputed and moved for follow-up.');
    }

    /**
     * Resolve a dispute by VERIFYING the session.
     */
    public function resolve(Dispute $dispute): RedirectResponse
    {
        $appointment = $dispute->appointment;

        // Call our centralized service to handle the complex logic
        list($success, $message) = $this->verificationService->verifySession($appointment);

        if (!$success) {
            return redirect()->back()->with('error', $message);
        }

        // All checks passed, resolve the dispute and verify the appointment
        $appointment->update(['status' => 'verified']);
        $dispute->update(['status' => 'resolved']);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($appointment)
            ->log("Resolved dispute and VERIFIED session for client '{$appointment->client->name}'");

        return redirect()->back()->with('status', 'Dispute resolved. Session has been verified successfully!');
    }

    /**
     * Resolve a dispute by CANCELLING the session.
     */
    public function cancel(Dispute $dispute): RedirectResponse
    {
        $appointment = $dispute->appointment;

        // Cancel the appointment (no credits used) and resolve the dispute
        $appointment->update(['status' => 'cancelled']);
        $dispute->update(['status' => 'resolved']);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($appointment)
            ->log("Resolved dispute and CANCELLED session for client '{$appointment->client->name}'");

        return redirect()->back()->with('status', 'Dispute resolved. Session has been cancelled.');
    }
}

