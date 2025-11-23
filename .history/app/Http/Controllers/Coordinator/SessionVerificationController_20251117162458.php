<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionVerificationController extends Controller
{
    /**
     * عرض الحصص بانتظار المراجعة الخاصة بعملاء هذا المنسق فقط
     */
    public function index()
    {
        $coordinator = Auth::user();
        
        // 1. جلب IDs العملاء الذين يديرهم هذا المنسق فقط
        $managedClientIds = $coordinator->managedClients()->pluck('id');

        // 2. جلب الحصص التي بانتظار المراجعة لهؤلاء العملاء فقط
        $pendingSessions = Appointment::where('status', 'pending_verification')
                                      ->whereIn('client_id', $managedClientIds) // ** الأمان **
                                      ->with('client', 'teacher')
                                      ->orderBy('start_time', 'desc')
                                      ->paginate(15);
                                      
        return view('coordinator.sessions.index', [
            'sessions' => $pendingSessions,
        ]);
    }

    /**
     * اعتماد حصة مكتملة
     */
    public function verify(Appointment $appointment)
    {
        // ** الأمان: التأكد أن هذه الحصة تابعة لعميل يملكه المنسق **
        if ($appointment->client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء');
        }

        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'تمت معالجة هذه الحصة مسبقاً.']);
        }

        try {
            DB::transaction(function () use ($appointment) {
                
                // (نفس منطق المدير بالضبط)
                if ($appointment->completion_status === 'completed') {
                    $subscription = Subscription::where('user_id', $appointment->client_id)
                        ->where('status', 'active')
                        ->where('ends_at', '>', now())
                        ->whereColumn('lessons_used', '<', 'total_lessons')
                        ->first();
                    
                    if ($subscription) {
                        $subscription->increment('lessons_used');
                        $subscription->refresh();
                        
                        if ($subscription->lessons_used >= $subscription->total_lessons) {
                            $subscription->status = 'expired';
                            $subscription->save();
                        }
                    }
                }
                
                $appointment->status = 'verified';
                $appointment->save();
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'حدث خطأ. ' . $e->getMessage()]);
        }

        return redirect()->route('coordinator.sessions.verify.index')->with('status', 'تمت مراجعة الحصة بنجاح.');
    }
/**
     * إلغاء الحصة بشكل نهائي
     * (Hard Reject)
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        // 1. ** الأمان: التأكد أن هذه الحصة تابعة لعميل يملكه المنسق **
        if ($appointment->client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء');
        }

        // 2. التأكد أن الحصة بانتظار المراجعة
        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'تمت معالجة هذه الحصة مسبقاً.']);
        }

        try {
            DB::transaction(function () use ($appointment) {
                // 3. تغيير الحالة إلى "ملغاة"
                $appointment->status = 'cancelled';
                $appointment->save();

                // 4. ** تسجيل النشاط **
                activity()
                    ->causedBy(Auth::user())
                    ->performedOn($appointment->client)
                    ->withProperties(['teacher_name' => $appointment->teacher->name, 'topic' => $appointment->topic])
                    ->log("قام بالإلغاء النهائي لحصة العميل {$appointment->client->name}");
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'حدث خطأ. ' . $e->getMessage()]);
        }

        // 5. إرجاع رسالة نجاح
        return redirect()->route('coordinator.sessions.verify.index')->with('status', 'تم إلغاء الحصة بنجاح.');
    }
}