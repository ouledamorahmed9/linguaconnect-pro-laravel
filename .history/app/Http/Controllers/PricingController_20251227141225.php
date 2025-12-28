<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; // Import Log

class PricingController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Get IP with Localhost Fallback
        $ip = $request->ip();
        if ($ip == '127.0.0.1' || $ip == '::1') {
            $ip = '197.57.0.0'; // Default to Egypt for testing
        }

        // 2. Fetch Location
        try {
            // Use 'pro.ip-api.com' if you have a key, otherwise 'http' (not https) for free tier
            $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}");
            
            if ($response->successful()) {
                $locationData = $response->json();
                $countryCode = $locationData['countryCode'] ?? 'US';
                $countryName = $locationData['country'] ?? 'Global';
            } else {
                throw new \Exception('API returned status: ' . $response->status());
            }
            
        } catch (\Exception $e) {
            // Log the error so we know why it failed (check storage/logs/laravel.log)
            Log::error("Pricing IP Error: " . $e->getMessage());
            
            // Fallback defaults
            $countryCode = 'US';
            $countryName = 'Global';
        }

        // 3. Get Pricing
        $pricing = $this->getPricesForCountry($countryCode);

        return view('pricing', [
            'plans' => $pricing['plans'],
            'currency' => $pricing['currency'],
            'country' => $countryName
        ]);
    }

    private function getPricesForCountry($code)
    {
        // Gulf Countries (SAR)
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

        // North Africa (EGP)
        // Added 'EG' explicitly to ensure Egypt gets picked up
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

        // Europe (EUR)
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

        // Default (USD)
        return [
            'currency' => '$',
            'plans' => [
                'basic' => ['price' => 30, 'period' => 'month'],
                'advanced' => ['price' => 70, 'period' => 'month'],
                'intensive' => ['price' => 120, 'period' => 'month'],
            ]
        ];
    }
}