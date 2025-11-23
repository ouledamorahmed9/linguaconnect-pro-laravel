<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Appointment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisputeController extends Controller
{
    /**
     * عرض النزاعات التي فتحها هذا المنسق
     */
    public function index()
    {
        $coordinator = Auth::user();

        // ** الأمان: جلب النزاعات التي أنشأها هذا المنسق فقط **
        $disputes = Dispute::where('admin_id', $coordinator->id) // (نستخدم 'admin_id' لتخزين 'coordinator_id')
                           ->where('status', 'open')
                           ->with('appointment.client', 'appointment.teacher')
                           ->orderBy('created_at', 'desc')
                           ->paginate(15);
                           
        return view('coordinator.disputes.index', [
            'disputes' => $disputes,
        ]);
    }

    /**
     * إنشاء نزاع جديد (رفض الحصة)
     */
    public function store(Request $request, Appointment $appointment)
    {
        // ** الأمان: التأكد أن هذه الحصة تابعة لعميل يملكه المنسق **
        if ($appointment->client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء');
        }

        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'تمت معالجة هذه الحصة مسبقاً.']);
        }

        $reason = $request->input('reason', 'المنسق رفض هذه الحصة.');

        try {
            DB::transaction(function () use ($appointment, $reason) {
                Dispute::create([
                    'appointment_id' => $appointment->id,
                    'admin_id' => Auth::id(), // ** هام: نسجل هوية المنسق هنا **
                    'reason' => $reason,
                    'status' => 'open',
                ]);

                $appointment->status = 'disputed';
                $appointment->save();
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'حدث خطأ. ' . $e->getMessage()]);
        }

        return redirect()->route('coordinator.sessions.verify.index')
                         ->with('status', 'تم رفض الحصة وإرسالها إلى صفحة النزاعات.');
    }

    /**
     * حل النزاع (الموافقة على الحصة)
     */
    public function resolve(Dispute $dispute)
    {
        // ** الأمان: التأكد أن هذا المنسق هو من فتح النزاع **
        if ($dispute->admin_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء');
        }

        try {
            DB::transaction(function () use ($dispute) {
                $appointment = $dispute->appointment;

                // (نفس منطق المدير بالضبط)
                if ($appointment->completion_status === 'completed') {
                    $subscription = Subscription::where('user_id', $appointment->client_id)
                        ->where('status', 'active')
                        ->where('ends_at', '>', now())
                        ->whereColumn('lessons_used', '<', 'total_lessons')
                        ->first();
                    
                    if ($subscription) $subscription->increment('lessons_used');
                }
                
                $appointment->status = 'verified';
                $appointment->save();

                $dispute->status = 'resolved';
                $dispute->save();
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'حدث خطأ. ' . $e->getMessage()]);
        }

        return redirect()->route('coordinator.disputes.index')->with('status', 'تم حل النزاع واعتماد الحصة.');
    }

    /**
     * إلغاء النزاع (تأكيد الرفض)
     */
    public function cancel(Dispute $dispute)
    {
        // ** الأمان: التأكد أن هذا المنسق هو من فتح النزاع **
        if ($dispute->admin_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء');
        }
        
        try {
            DB::transaction(function () use ($dispute) {
                $appointment = $dispute->appointment;
                $appointment->status = 'cancelled';
                $appointment->save();

                $dispute->status = 'resolved';
                $dispute->save();
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'حدث خطأ. ' . $e->getMessage()]);
        }

        return redirect()->route('coordinator.disputes.index')->with('status', 'تم حل النزاع وإلغاء الحصة.');
    }
}