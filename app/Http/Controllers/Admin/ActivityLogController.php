<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * عرض سجل أنشطة المنسقين
     */
    public function index(Request $request): View
    {
        // 1. نبدأ بجلب السجلات
        $query = Activity::query()
                        ->where('causer_type', \App\Models\User::class) // نريد فقط الأنشطة التي قام بها مستخدم
                        ->with(['causer', 'subject']); // جلب بيانات "الفاعل" (المنسق) و "المفعول به" (العميل مثلاً)

        // 2. فلترة بناءً على دور "المنسق"
        $query->whereHas('causer', function ($q) {
            $q->where('role', 'coordinator');
        });

        // 3. (احترافي) إضافة بحث/فلترة إذا طلب المدير
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('causer', function ($q_user) use ($search) {
                      $q_user->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // 4. جلب أحدث السجلات أولاً مع ترقيم الصفحات
        $activities = $query->latest()->paginate(20)->withQueryString();

        return view('admin.activity.index', [
            'activities' => $activities,
            'search' => $request->input('search', ''),
        ]);
    }
}