<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Dispute; // Import the Dispute model
    use Illuminate\Http\Request;
    use Illuminate\View\View;

    class DisputeManagementController extends Controller
    {
        /**
         * Display a listing of all open disputes.
         */
        public function index(): View
        {
            $openDisputes = Dispute::with(['appointment.teacher', 'appointment.client', 'admin'])
                ->where('status', 'open')
                ->orderBy('created_at', 'asc')
                ->get();

            return view('admin.disputes.index', compact('openDisputes'));
        }
    }
    
