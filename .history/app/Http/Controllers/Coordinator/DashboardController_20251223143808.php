<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // <-- تأكد من إضافة هذا السطر لاستخدام Str

class DashboardController extends Controller
{
    /**
     * عرض لوحة تحكم المنسق
     */
    public function index()
    {
        $coordinator = Auth::user();

        // ==========================================
        // 1. منطق نظام الإحالة (Referral System) - الجديد
        // ==========================================
        
        // إذا لم يكن لدى المنسق كود إحالة بعد، قم بإنشاء واحد الآن وحفظه
        if (is_null($coordinator->referral_code)) {
            // نأخذ أول 3 أحرف من الاسم أو نستخدم 'COORD' كاحتياطي
            $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $coordinator->name) ?: 'COORD', 0, 3));
            $coordinator->referral_code = $prefix . '-' . strtoupper(Str::random(5));
            $coordinator->save();
        }

        // تجهيز الرابط للعرض في الواجهة
        $referralLink = route('referral.link', ['code' => $coordinator->referral_code]);

        // حساب عدد الطلاب الذين سجلوا عن طريق هذا المنسق
        $referralCount = $coordinator->referrals()->count();


        // ==========================================
        // 2. منطق الإحصائيات القديم (كما هو)
        // ==========================================

        // جلب IDs العملاء الذين يديرهم هذا المنسق فقط
        $managedClientIds = $coordinator->managedClients()->pluck('id');

        // حساب إحصائيات العملاء
        $totalClients = $managedClientIds->count();

        // حساب الاشتراكات النشطة لعملاء هذا المنسق فقط
        $activeSubscriptions = Subscription::whereIn('user_id', $managedClientIds)
                                           ->where('status', 'active')
                                           ->where('ends_at', '>', now())
                                           ->count();

        // حساب الحصص التي بانتظار المراجعة لعملاء هذا المنسق فقط
        $pendingSessions = Appointment::whereIn('client_id', $managedClientIds)
                                      ->where('status', 'pending_verification')
                                      ->count();

        return view('coordinator.dashboard', [
            'totalClients' => $totalClients,
            'activeSubscriptions' => $activeSubscriptions,
            'pendingSessions' => $pendingSessions,
            // المتغيرات الجديدة الخاصة بالإحالة
            'referralLink' => $referralLink,
            'referralCount' => $referralCount,
        ]);
    }
}