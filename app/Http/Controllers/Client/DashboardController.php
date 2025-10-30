<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the client's main dashboard.
     */
    public function index(): View
    {
        // This keeps the logic consistent. In the future, we can pass
        // dynamic data from here instead of using the @inject directive.
        return view('dashboard');
    }
}
