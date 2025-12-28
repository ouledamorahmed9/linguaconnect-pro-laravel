<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View; // Import the View class for type-hinting

class PricingController extends Controller
{
    /**
     * Display the pricing page.
     *
     * This method is responsible for returning the view
     * that contains our pricing plans.
     */
    public function index(): View
    {
        // This tells Laravel to find and return the 'pricing.blade.php' file
        // located in the resources/views directory.
        return view('pricing');
    }
}
