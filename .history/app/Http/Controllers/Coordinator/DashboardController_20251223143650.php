<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * عرض لوحة تحكم المنسق
     */
public function index()
    {
        $user = Auth::user();

        // 1. Auto-generate referral code for existing coordinators if missing
        if (is_null($user->referral_code)) {
            // Create a simple code: First 3 letters of name + Random 5 chars
            $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $user->name) ?: 'COORD', 0, 3));
            $user->referral_code = $prefix . '-' . strtoupper(\Illuminate\Support\Str::random(5));
            $user->save();
        }

        // 2. Prepare the link to show in the view
        $referralLink = route('referral.link', ['code' => $user->referral_code]);

        // 3. Count how many students they have referred
        $referralCount = $user->referrals()->count();

        return view('coordinator.dashboard', compact('referralLink', 'referralCount'));
    }}