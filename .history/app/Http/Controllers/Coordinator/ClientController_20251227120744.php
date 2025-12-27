<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudySubject; // <--- Import this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * عرض قائمة العملاء الذين يديرهم هذا المنسق
     */
    public function index(): View
    {
        $coordinator = Auth::user();
        $clients = $coordinator->managedClients()
                            ->latest()
                            ->paginate(15);

        return view('coordinator.clients.index', compact('clients'));
    }

    /**
     * عرض نموذج إنشاء عميل جديد
     */
    public function create(): View
    {
        // Fetch active subjects for the dropdown
        $studySubjects = StudySubject::active()->ordered()->get();

        return view('coordinator.clients.create', compact('studySubjects'));
    }

    /**
     * تخزين عميل جديد في قاعدة البيانات
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'], // <--- New Validation
            'study_subject_id' => ['required', 'exists:study_subjects,id'], // <--- New Validation
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $client = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone, // <--- Saving Phone
            'study_subject_id' => $request->study_subject_id, // <--- Saving Subject
            'password' => Hash::make($request->password),
            'role' => 'client',
            'created_by_user_id' => Auth::id(),
        ]);

        // --- ** ابدأ الإضافة هنا ** ---
        activity()
            ->causedBy(Auth::user()) // (المنسق)
            ->performedOn($client) // (العميل الجديد)
            ->log("أنشأ حساب عميل جديد: {$client->name}");
        // --- ** انتهت الإضافة ** ---

        return redirect()->route('coordinator.clients.index')->with('status', 'تم إنشاء حساب العميل بنجاح!');
    }

    /**
     * عرض نموذج تعديل العميل
     */
    public function edit(User $client): View
    {
        if ($client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $activeSubscription = $client->subscriptions()
                                     ->where('status', 'active')
                                     ->latest('starts_at')
                                     ->first();

        // Fetch subjects for the dropdown
        $studySubjects = StudySubject::active()->ordered()->get();

        return view('coordinator.clients.edit', [
            'client' => $client,
            'subscription' => $activeSubscription,
            'studySubjects' => $studySubjects, // <--- Pass this
        ]);
    }

    /**
     * تحديث بيانات العميل
     */
    public function update(Request $request, User $client): RedirectResponse
    {
        if ($client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالقيام بهذا الإجراء');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($client->id)],
            'phone' => ['required', 'string', 'max:20'], // <--- Validate Phone
            'study_subject_id' => ['required', 'exists:study_subjects,id'], // <--- Validate Subject
        ]);

        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'study_subject_id' => $request->study_subject_id,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $client->update(['password' => Hash::make($request->password)]);
        }

        // --- ** ابدأ الإضافة هنا ** ---
        activity()
            ->causedBy(Auth::user())
            ->performedOn($client)
            ->log("حدّث الملف الشخصي للعميل: {$client->name}");
        // --- ** انتهت الإضافة ** ---

        return redirect()->route('coordinator.clients.index')->with('status', 'تم تحديث ملف العميل بنجاح!');
    }

    /**
     * حذف العميل
     */
    public function destroy(User $client): RedirectResponse
    {
        if ($client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالقيام بهذا الإجراء');
        }

        // --- ** ابدأ الإضافة هنا ** ---
        // (نسجل النشاط قبل الحذف)
        activity()
            ->causedBy(Auth::user())
            ->performedOn($client)
            ->log("حذف العميل: {$client->name} (Email: {$client->email})");
        // --- ** انتهت الإضافة ** ---

        $client->delete();
        return redirect()->route('coordinator.clients.index')->with('status', 'تم حذف العميل بنجاح.');
    }
}