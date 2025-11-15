<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $clients = $coordinator->managedClients() // (استخدام العلاقة التي أنشأناها)
                            ->latest()
                            ->paginate(15);

        return view('coordinator.clients.index', compact('clients'));
    }

    /**
     * عرض نموذج إنشاء عميل جديد
     */
    public function create(): View
    {
        return view('coordinator.clients.create');
    }

    /**
     * تخزين عميل جديد في قاعدة البيانات
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client', // تحديد الدور كـ 'client'
            'created_by_user_id' => Auth::id(), // ** أهم سطر: ربط العميل بالمنسق الحالي **
        ]);

        return redirect()->route('coordinator.clients.index')->with('status', 'تم إنشاء حساب العميل بنجاح!');
    }

    /**
     * عرض نموذج تعديل العميل
     * (ملاحظة: سنقوم بتطوير هذه الصفحة في الخطوة التالية)
     */
    public function edit(User $client): View
    {
        // ** تأمين احترافي: التأكد من أن المنسق يمتلك هذا العميل **
        if ($client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        // في المستقبل، سنضيف الاشتراكات هنا. الآن، سنعرض فقط صفحة التعديل.
        return view('coordinator.clients.edit', compact('client'));
    }

    /**
     * تحديث بيانات العميل
     */
    public function update(Request $request, User $client): RedirectResponse
    {
        // ** تأمين احترافي: التأكد من أن المنسق يمتلك هذا العميل **
        if ($client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالقيام بهذا الإجراء');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($client->id)],
        ]);

        $client->update($request->only('name', 'email'));

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $client->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('coordinator.clients.index')->with('status', 'تم تحديث ملف العميل بنجاح!');
    }

    /**
     * حذف العميل
     */
    public function destroy(User $client): RedirectResponse
    {
        // ** تأمين احترافي: التأكد من أن المنسق يمتلك هذا العميل **
        if ($client->created_by_user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالقيام بهذا الإجراء');
        }

        $client->delete();
        return redirect()->route('coordinator.clients.index')->with('status', 'تم حذف العميل بنجاح.');
    }
}