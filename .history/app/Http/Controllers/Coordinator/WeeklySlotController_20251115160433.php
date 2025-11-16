<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WeeklySlot;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WeeklySlotController extends Controller
{
    /**
     * عرض صفحة إدارة الجدول الأسبوعي.
     */
    public function index(Request $request)
    {
        $coordinator = Auth::user();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $selectedTeacherId = $request->input('teacher_id');
        $selectedTeacher = null;
        
        // المتغيرات التي سنرسلها للواجهة
        $myClients = collect(); // عملاء المنسق المتاحين للإضافة
        $weeklySlots = collect(); // كل الحصص لعرض الجدول
        $calendarEvents = []; 
        $teacherStats = null;

        if ($selectedTeacherId) {
            $selectedTeacher = User::find($selectedTeacherId);

            if ($selectedTeacher && $selectedTeacher->hasRole('teacher')) {
                
                // 1. جلب عملاء المنسق (فقط) الذين تم ربطهم بهذا المعلم ولديهم اشتراك نشط
                // هذا لملء القائمة المنسدلة "إضافة حصة"
                $myClients = $coordinator->managedClients()
                    ->whereHas('teachers', function($q) use ($selectedTeacherId) {
                        $q->where('user_id', $selectedTeacherId); // المعلم هو user_id في جدول العلاقة
                    })
                    ->get()
                    ->filter(function ($client) {
                        return $client->hasActiveSubscription();
                    });

                // 2. جلب "كل" الحصص لهذا المعلم (لعرضها في الجدول وتجنب التعارض)
                // حتى لو كانت لعملاء منسق آخر، يجب أن يراها ليعرف أن الوقت مشغول
                $weeklySlots = WeeklySlot::where('teacher_id', $selectedTeacher->id)
                                ->with('client')
                                ->get()
                                ->sortBy([
                                    ['day_of_week', 'asc'],
                                    ['start_time', 'asc'],
                                ])
                                ->groupBy('client.name');
                
                // 3. إعداد البيانات للتقويم الرسومي (Calendar View)
                $allSlots = WeeklySlot::where('teacher_id', $selectedTeacher->id)
                                      ->with('client', 'teacher')
                                      ->get();
                
                $calendarEvents = $allSlots->map(function ($slot) use ($coordinator) {
                    $clientName = $slot->client ? $slot->client->name : 'عميل غير محدد';
                    $subject = $slot->teacher->subject ?? 'حصة';
                    
                    // تمييز حصص عملاء هذا المنسق بلون مختلف
                    $isMyClient = ($slot->client && $slot->client->created_by_user_id === $coordinator->id);
                    $color = $isMyClient ? '#4f46e5' : '#9ca3af'; // أزرق لعملائي، رمادي للآخرين

                    return [
                        'id' => $slot->id,
                        'title' => "{$clientName} ({$subject})",
                        'daysOfWeek' => [$slot->day_of_week],
                        'startTime' => $slot->start_time,
                        'endTime' => $slot->end_time,
                        'color' => $color,
                        'allDay' => false,
                        'clientName' => $clientName,
                        'subject' => $subject,
                    ];
                });
            } else {
                $selectedTeacherId = null;
            }
        }

        return view('coordinator.roster.index', [
            'teachers' => $teachers,
            'selectedTeacherId' => $selectedTeacherId,
            'selectedTeacher' => $selectedTeacher,
            'clients' => $myClients, // قائمة العملاء المسموح بإضافتهم
            'weeklySlots' => $weeklySlots, // كل الحصص للعرض
            'calendarEvents' => json_encode($calendarEvents),
        ]);
    }

    /**
     * حفظ حصة أسبوعية جديدة.
     */
    public function store(Request $request)
    {
        $coordinator = Auth::user();

        $validated = $request->validate([
            'teacher_id' => 'required|integer|exists:users,id',
            'client_id' => 'required|integer|exists:users,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
        ]);

        // ** أمان: التأكد أن العميل يتبع لهذا المنسق **
        $client = User::find($validated['client_id']);
        if ($client->created_by_user_id !== $coordinator->id) {
            abort(403, 'غير مصرح لك بإضافة حصص لهذا العميل.');
        }

        // التحقق من التعارض (Overlap) - نفس كود المدير
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHour();
    
        $isOverlap = WeeklySlot::where('teacher_id', $validated['teacher_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($startTime, $endTime) {
                $query->where(function($q) use ($startTime) {
                    $q->where('start_time', '<', $startTime->format('H:i:s'))
                      ->where('end_time', '>', $startTime->format('H:i:s'));
                })->orWhere(function($q) use ($endTime) {
                    $q->where('start_time', '<', $endTime->format('H:i:s'))
                      ->where('end_time', '>', $endTime->format('H:i:s'));
                })->orWhere(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '>=', $startTime->format('H:i:s'))
                      ->where('end_time', '<=', $endTime->format('H:i:s'));
                });
            })->exists();
    
        if ($isOverlap) {
            return redirect()->back()->withErrors(['message' => 'هذا التوقيت يتعارض مع حصة أخرى موجودة لهذا المعلم.']);
        }
    
        WeeklySlot::create([
            'teacher_id' => $validated['teacher_id'],
            'client_id' => $validated['client_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
        ]);
        
    
        return redirect()->back()->with('status', 'تمت إضافة الحصة الأسبوعية بنجاح.');
    }

    /**
     * حذف حصة أسبوعية.
     */
    public function destroy(WeeklySlot $weeklySlot)
    {
        $coordinator = Auth::user();

        // ** أمان: التأكد أن الحصة تخص عميلاً يتبع لهذا المنسق **
        if ($weeklySlot->client->created_by_user_id !== $coordinator->id) {
            abort(403, 'لا يمكنك حذف حصص لعملاء لا تديرهم.');
        }

        $weeklySlot->delete();
        return redirect()->back()->with('status', 'تم حذف الحصة الأسبوعية.');
    }
}