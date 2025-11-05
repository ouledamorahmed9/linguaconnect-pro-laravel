<?php

    namespace App\Http\Controllers;

    use App\Models\Appointment;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\View\View;

    class ProgressReportController extends Controller
    {
        /**
         * Display the client's progress reports.
         */
        public function index(): View
        {
            // THIS IS THE FIX:
            // Only show reports that are VERIFIED, or show issues that are DISPUTED/CANCELLED.
            // We no longer show 'logged' (pending) reports to the client.
            $reports = Appointment::with('teacher')
                ->where('client_id', Auth::id())
                ->whereIn('status', ['verified', 'disputed', 'cancelled', 'no_show'])
                ->orderBy('start_time', 'desc')
                ->get();
                
            return view('progress-reports.index', compact('reports'));
        }
    }
    

