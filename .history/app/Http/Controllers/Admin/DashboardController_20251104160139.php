<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User; // <-- ** STEP 1: Make sure User model is imported **
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // --- THIS IS THE FIX ---

        // 1. Get Pending Sessions (already exists)
        $pendingSessions = Appointment::where('status', 'pending_verification')->count();

        // 2. Get Total Clients
        $totalClients = User::where('role', 'client')->count();

        // 3. Get Total Teachers
        $totalTeachers = User::where('role', 'teacher')->count();
        
        // --- END OF FIX ---

        return view('admin.dashboard', [
            'pendingSessions' => $pendingSessions,
            'totalClients' => $totalClients,     // <-- 4. Pass the new data
            'totalTeachers' => $totalTeachers,   // <-- 4. Pass the new data
        ]);
    }
}
