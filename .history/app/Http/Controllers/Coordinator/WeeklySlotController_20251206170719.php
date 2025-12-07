<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WeeklySlot;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

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
        
        $myClients = collect(); // عملاء المنسق المتاحين للإضافة
        $weeklySlots = collect(); // كل الحصص لعرض الجدول
        $calendarEvents = []; 

        if ($selectedTeacherId) {
            $selectedTeacher = User::find($selectedTeacherId);

            if ($selectedTeacher && $selectedTeacher->hasRole('teacher')) {
                // عملاء المنسق المرتبطون بالمعلم ولديهم اشتراك نشط
                $myClients = $coordinator->managedClients()
                    ->whereHas('teachers', function($q) use ($selectedTeacherId) {
                        $q->where('user_id', $selectedTeacherId);
                    })
                    ->get()
                    ->filter(fn ($client) => $client->hasActiveSubscription());

                // جميع الحصص للعرض (مع الطلاب)
                $weeklySlots = WeeklySlot::where('teacher_id', $selectedTeacher->id)
                    ->with(['students', 'client', 'teacher'])
                    ->orderBy('day_of_week')
                    ->orderBy('start_time')
                    ->get()
                    ->groupBy('day_of_week');

                // أحداث التقويم
                $allSlots = WeeklySlot::where('teacher_id', $selectedTeacher->id)
                    ->with(['students', 'teacher', 'client'])
                    ->get();
                
                $calendarEvents = $allSlots->map(function ($slot) use ($coordinator) {
                    $names = $slot->students->pluck('name');
                    $clientName = $names->isNotEmpty()
                        ? $names->implode(', ')
                        : ($slot->client ? $slot->client->name : 'عميل غير محدد');
                    $subject = $slot->teacher->subject ?? 'حصة';

                    $isMyClient = $slot->students->contains(fn($s) => $s->created_by_user_id === $coordinator->id)
                                 || ($slot->client && $slot->client->created_by_user_id === $coordinator->id);
                    $color = $isMyClient ? '#4f46e5' : '#9ca3af';

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
            'clients' => $myClients,
            'weeklySlots' => $weeklySlots,
            'calendarEvents' => json_encode($calendarEvents),
        ]);
    }

    /**
     * حفظ حصة أسبوعية جديدة (متعددة الطلاب).
     */
    public function store(Request $request)
    {
        $coordinator = Auth::user();

        $validated = $request->validate([
            'teacher_id' => 'required|integer|exists:users,id',
            'students' => ['required', 'array', 'min:1'],
            'students.*' => [
                'integer',
                Rule::exists('users', 'id'),
            ],
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
        ]);

        // تأكد أن كل طالب يتبع لهذا المنسق
        $studentIds = collect($validated['students']);
        $owned = $coordinator->managedClients()->whereIn('id', $studentIds)->pluck('id');
        if ($owned->count() !== $studentIds->count()) {
            abort(403, 'غير مصرح لك بإضافة حصص لعملاء لا تديرهم.');
        }

        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHour();

        // تحقق التعارض
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

        // إنشاء الحصة (client_id = أول طالب للتوافق السابق)
        $weeklySlot = WeeklySlot::create([
            'teacher_id' => $validated['teacher_id'],
            'client_id' => $validated['students'][0] ?? null,
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
        ]);

        $weeklySlot->students()->sync($validated['students']);

        return redirect()->back()->with('status', 'تمت إضافة الحصة الأسبوعية بنجاح.');
    }

    /**
     * حذف حصة أسبوعية.
     */
    public function destroy(WeeklySlot $weeklySlot)
    {
        $coordinator = Auth::user();

        // أمان: تحقق أن جميع الطلاب/العميل ضمن إدارة هذا المنسق
        $ownsAny = $weeklySlot->students->contains(fn($s) => $s->created_by_user_id === $coordinator->id)
                   || ($weeklySlot->client && $weeklySlot->client->created_by_user_id === $coordinator->id);
        if (!$ownsAny) {
            abort(403, 'لا يمكنك حذف حصص لعملاء لا تديرهم.');
        }

        $weeklySlot->delete();
        return redirect()->back()->with('status', 'تم حذف الحصة الأسبوعية.');
    }
}