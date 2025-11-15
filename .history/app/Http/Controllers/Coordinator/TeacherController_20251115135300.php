<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TeacherController extends Controller
{
    /**
     * عرض قائمة بجميع المعلمين المتاحين في المنصة
     */
    public function index(): View
    {
        $teachers = User::where('role', 'teacher')
                        ->withCount('clients') // عدد العملاء الكلي (للمعلومات العامة)
                        ->orderBy('name')
                        ->paginate(15);

        return view('coordinator.teachers.index', compact('teachers'));
    }

    /**
     * عرض صفحة إدارة عملاء المنسق لهذا المعلم
     */
    public function edit(Request $request, User $teacher): View
    {
        // التأكد أن المستخدم هو معلم
        if (!$teacher->hasRole('teacher')) {
            abort(404, 'User is not a teacher.');
        }

        $coordinator = Auth::user();
        $search = $request->input('search');

        // 1. جلب IDs العملاء المعينين حالياً لهذا المعلم
        $assignedClientIds = $teacher->clients()->pluck('users.id')->toArray();

        // 2. جلب عملاء "هذا المنسق" فقط
        $managedClients = $coordinator->managedClients()
            ->where(function ($query) use ($search) {
                // فلترة العملاء الذين لديهم اشتراك نشط فقط
                $query->whereHas('subscriptions', function ($subQuery) {
                    $subQuery->where('status', 'active')
                             ->where('ends_at', '>', now())
                             ->whereColumn('lessons_used', '<', 'total_lessons');
                });
                
                // تطبيق البحث (إذا وجد)
                if ($search) {
                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                    });
                }
            })
            ->orderBy('name')
            ->paginate(20)
            ->appends(['search' => $search]);

        // 3. تمرير البيانات للواجهة
        return view('coordinator.teachers.edit', [
            'teacher' => $teacher,
            'managedClients' => $managedClients, // عملاء المنسق فقط
            'assignedClientIds' => $assignedClientIds, // العملاء المربوطون حالياً بالمعلم
            'search' => $search,
        ]);
    }
}