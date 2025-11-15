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
        $coordinator = Auth::user();

        // 1. جلب IDs العملاء الذين يديرهم هذا المنسق فقط
        $managedClientIds = $coordinator->managedClients()->pluck('id');

        // 2. حساب إحصائيات العملاء
        $totalClients = $managedClientIds->count();

        // 3. حساب الاشتراكات النشطة لعملاء هذا المنسق فقط
        $activeSubscriptions = Subscription::whereIn('user_id', $managedClientIds)
                                            ->where('status', 'active')
                                            ->where('ends_at', '>', now())
                                            ->count();

        // 4. حساب الحصص التي بانتظار المراجعة لعملاء هذا المنسق فقط
        $pendingSessions = Appointment::whereIn('client_id', $managedClientIds)
                                      ->where('status', 'pending_verification')
                                      ->count();

        return view('coordinator.dashboard', [
            'totalClients' => $totalClients,
            'activeSubscriptions' => $activeSubscriptions,
            'pendingSessions' => $pendingSessions,
        ]);
    }
}