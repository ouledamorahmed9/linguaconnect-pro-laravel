<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Dispute; // Import the Dispute model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // Import View

class DisputeController extends Controller
{
    /**
     * Display a listing of all open disputes.
     * (This method was moved from DisputeManagementController)
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
    public function store(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $appointment->disputes()->create([
            'admin_id' => Auth::id(),
            'reason' => $validated['reason'],
            'status' => 'open',
        ]);

        $appointment->update(['status' => 'disputed']);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($appointment)
            ->log("Raised a dispute for session with reason: {$validated['reason']}");

        return redirect()->back()->with('status', 'Session has been disputed and moved for follow-up.');
    }
}

