<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SubscriptionController extends Controller
{
    /**
     * عرض نموذج إنشاء اشتراك جديد لعميل محدد.
     */
    public function create(User $client): View|RedirectResponse
    {
        // ** تأمين احترافي: التأكد من أن المنسق يمتلك هذا العميل **
        if ($client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $studySubjects = \App\Models\StudySubject::where('is_active', true)->get();
        return view('coordinator.subscriptions.create', compact('client', 'studySubjects'));
    }

    /**
     * تخزين الاشتراك الجديد في قاعدة البيانات.
     */
    public function store(Request $request, User $client): RedirectResponse
    {
        // ** تأمين احترافي: التأكد من أن المنسق يمتلك هذا العميل **
        if ($client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالقيام بهذا الإجراء');
        }

        $validated = $request->validate([
            'plan_type' => ['required', 'string', 'in:one_to_one,duo,vip,normal'],
            'target_language' => ['required', 'string'],
            'starts_at' => ['required', 'date'],
        ]);

        $lessonCredits = [
            'one_to_one' => 8,
            'duo' => 8,
            'vip' => 8,
            'normal' => 8,
        ];

        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = $startsAt->copy()->addMonth();

        $subscription = $client->subscriptions()->create([
            'plan_type' => $validated['plan_type'],
            'target_language' => $validated['target_language'],
            'total_lessons' => $lessonCredits[$validated['plan_type']],
            'lessons_used' => 0,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
        ]);

        // تسجيل النشاط (يُنسب للمنسق)
        activity()
            ->causedBy(Auth::user())
            ->performedOn($subscription)
            ->log("Assigned a new '{$subscription->plan_type}' subscription ({$subscription->target_language}) to client '{$client->name}'");

        return redirect()->route('coordinator.clients.edit', $client)->with('status', 'تم تعيين الاشتراك الجديد بنجاح!');
    }

    /**
     * حذف الاشتراك من قاعدة البيانات.
     */
    public function destroy(Subscription $subscription): RedirectResponse
    {
        // ** تأمين احترافي: التأكد من أن المنسق يمتلك العميل المرتبط بهذا الاشتراك **
        $client = $subscription->user;
        if ($client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالقيام بهذا الإجراء');
        }

        $clientId = $subscription->user_id;

        // --- ** ابدأ الإضافة هنا ** ---
        activity()
            ->causedBy(Auth::user())
            ->performedOn($subscription)
            ->withProperties(['client_name' => $client->name, 'plan' => $subscription->plan_type])
            ->log("ألغى اشتراك ({$subscription->plan_type}) للعميل {$client->name}");
        // --- ** انتهت الإضافة ** ---

        $subscription->delete();

        return redirect()->route('coordinator.clients.edit', $clientId)
            ->with('status', 'تم إلغاء الاشتراك بنجاح.');
    }
}