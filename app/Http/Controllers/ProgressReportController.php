<?php

namespace App\Http\Controllers;

use App\Models\Appointment; // Import the Appointment model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\View\View;

class ProgressReportController extends Controller
{
    /**
     * Display the client's progress reports.
     */
    public function index(): View
    {
        // Fetch all appointments for the logged-in client that have been completed
        // or have a status that indicates the session is over.
        $reports = Appointment::with('teacher')
            ->where('client_id', Auth::id())
            ->whereIn('status', ['completed', 'student_no_show', 'technical_issue'])
            ->orderBy('start_time', 'desc') // Order by most recent first
            ->get();
            
        return view('progress-reports.index', compact('reports'));
    }
}

