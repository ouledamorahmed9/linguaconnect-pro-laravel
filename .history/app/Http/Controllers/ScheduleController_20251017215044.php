<?php

namespace App\Http\Controllers;

use App\Models\Appointment; // Import the Appointment model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /**
     * Display the client's full schedule.
     */
    public function index(): View
    {
        // Get the ID of the currently logged-in user (the client)
        $clientId = Auth::id();

        // Fetch all appointments for this client, separated into upcoming and past
        $upcomingAppointments = Appointment::with('teacher')
            ->where('client_id', $clientId)
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->get();

        $pastAppointments = Appointment::with('teacher')
            ->where('client_id', $clientId)
            ->where('start_time', '<', now())
            ->orderBy('start_time', 'desc')
            ->get();

        // Pass the fetched data to the view
        return view('schedule.index', compact('upcomingAppointments', 'pastAppointments'));
    }
}

