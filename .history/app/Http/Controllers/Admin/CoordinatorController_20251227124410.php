<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CoordinatorController extends Controller
{
    /**
     * عرض قائمة بجميع المنسقين
     */
    public function index(Request $request): View // <--- Added Request dependency
    {
        $query = User::where('role', 'coordinator');

        // --- SEARCH LOGIC START ---
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        // --- SEARCH LOGIC END ---

        $coordinators = $query->withCount('managedClients')
                            ->latest()
                            ->paginate(15);

        return view('admin.coordinators.index', compact('coordinators'));
    }

    /**
     * عرض نموذج إنشاء منسق جديد
     */
    public function create(): View
    {
        return view('admin.coordinators.create');
    }

    /**
     * تخزين المنسق الجديد في قاعدة البيانات
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Create Instance
        $coordinator = new User();
        
        // 2. Assign Safe Fields
        $coordinator->name = $request->name;
        $coordinator->email = $request->email;
        $coordinator->password = Hash::make($request->password);
        $coordinator->email_verified_at = now();

        // 3. Force Role (Securely)
        $coordinator->role = 'coordinator';

        // 4. Save
        $coordinator->save();

        return redirect()->route('admin.coordinators.index')
            ->with('status', 'تم إنشاء حساب المنسق بنجاح!');
    }

    /**
     * حذف المنسق
     * (ملاحظة: عند الحذف، سيتم تحويل عملائه إلى 'null' بفضل قاعدة البيانات)
     */
    public function destroy(User $coordinator): RedirectResponse
    {
        // تأكيد أن هذا المستخدم هو 'coordinator' فعلاً
        if (!$coordinator->hasRole('coordinator')) {
            return back()->with('error', 'المستخدم المحدد ليس منسقاً.');
        }

        $coordinator->delete();
        return redirect()->route('admin.coordinators.index')->with('status', 'تم حذف المنسق بنجاح.');
    }
}