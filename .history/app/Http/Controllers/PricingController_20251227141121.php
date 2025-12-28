<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PricingController extends Controller
{
    /**
     * Display the pricing page with dynamic currency based on IP.
     */
public function index(Request $request): View
    {
        // 1. Get User IP
        $ip = $request->ip();
        
        if ($ip == '127.0.0.1' || $ip == '::1') {
            $ip = '197.57.0.0'; // Egypt IP for testing
        }

        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
            $locationData = $response->json();
            
            // --- ADD THIS LINE FOR DEBUGGING ---
            // This will stop the code and show you the result on the screen
            // dd($ip, $locationData); 
            // -----------------------------------

            $countryCode = $locationData['countryCode'] ?? 'US';
            $countryName = $locationData['country'] ?? 'Global';
        } catch (\Exception $e) {
            
            // --- IF IT FAILS, DUMP THE ERROR ---
            // dd('API FAILED', $e->getMessage());
            // -----------------------------------

            $countryCode = 'US';
            $countryName = 'Global';
        }
    
    /**
     * Helper to return pricing based on country code
     */
    private function getPricesForCountry($code)
    {
        // Group 1: Gulf Countries (High Income) -> SAR
        if (in_array($code, ['SA', 'AE', 'QA', 'KW', 'BH', 'OM'])) {
            return [
                'currency' => 'SAR', 
                'plans' => [
                    'basic' => ['price' => 150, 'period' => 'شهر'],
                    'advanced' => ['price' => 350, 'period' => 'شهر'],
                    'intensive' => ['price' => 600, 'period' => 'شهر'],
                ]
            ];
        }

        // Group 2: North Africa (Egypt, etc) -> EGP (Lower cost)
        if (in_array($code, ['EG', 'TN', 'DZ', 'MA', 'SD'])) {
            return [
                'currency' => 'EGP',
                'plans' => [
                    'basic' => ['price' => 450, 'period' => 'شهر'],
                    'advanced' => ['price' => 950, 'period' => 'شهر'],
                    'intensive' => ['price' => 1800, 'period' => 'شهر'],
                ]
            ];
        }

        // Group 3: Europe -> EUR
        if (in_array($code, ['DE', 'FR', 'IT', 'ES', 'NL', 'BE'])) {
            return [
                'currency' => '€',
                'plans' => [
                    'basic' => ['price' => 29, 'period' => 'month'],
                    'advanced' => ['price' => 59, 'period' => 'month'],
                    'intensive' => ['price' => 99, 'period' => 'month'],
                ]
            ];
        }

        // Default: Rest of World -> USD
        return [
            'currency' => '$',
            'plans' => [
                'basic' => ['price' => 30, 'period' => 'month'],
                'advanced' => ['price' => 70, 'period' => 'month'],
                'intensive' => ['price' => 120, 'period' => 'month'],
            ]
        ];
    }
}}