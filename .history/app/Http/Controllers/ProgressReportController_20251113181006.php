<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment; // <-- Import the Appointment (log) model

class ProgressReportController extends Controller
{
    /**
     * Display a paginated list of the client's lesson history.
     */
    public function index()
    {
        $client = Auth::user();

        // ** THIS IS THE NEW LOGIC **
        
        // We fetch all appointments (logs) for this client that have been
        // processed by the admin (either 'verified' or 'cancelled' or 'disputed').
        // We do NOT show 'pending_verification'.
        $lessons = Appointment::where('client_id', $client->id)
                            ->whereIn('status', ['verified', 'disputed', 'cancelled'])
                            ->with('teacher') // Eager load the teacher's name
                            ->orderBy('start_time', 'desc') // Show newest lessons first
                            ->paginate(10); // Paginate the results

        return view('progress-reports.index', [
            'lessons' => $lessons,
        ]);
    }
}