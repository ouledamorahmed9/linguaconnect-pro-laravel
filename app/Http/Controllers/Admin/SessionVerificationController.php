<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\SessionVerificationService; // Import our new service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SessionVerificationController extends Controller
{
    // Hold an instance of our new service
    protected $verificationService;

    // Use Dependency Injection to automatically load the service
    public function __construct(SessionVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

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
     * Verify a logged session.
     */
    public function verify(Appointment $appointment): RedirectResponse
    {
        // Call our centralized service to do the complex logic
        list($success, $message) = $this->verificationService->verifySession($appointment);

        if (!$success) {
            return redirect()->back()->with('error', $message);
        }

        // If successful, update the appointment status
        $appointment->update(['status' => 'verified']);

        // Log this critical admin action
        activity()
            ->causedBy(Auth::user())
            ->performedOn($appointment)
            ->log("Verified session for client '{$appointment->client->name}' with teacher '{$appointment->teacher->name}'");

        return redirect()->back()->with('status', $message);
    }
}

