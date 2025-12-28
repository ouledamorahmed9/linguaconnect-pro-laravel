<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(Request $request): View
    {
        $currency = '$';
        $countryName = 'Global'; 

        // FETCH PLANS FROM CONFIG
        $plans = config('plans');

        return view('pricing', [
            'plans' => $plans,
            'currency' => $currency,
            'country' => $countryName
        ]);
    }
}