<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WeeklySlot;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    /**
     * Display the teacher's weekly schedule.
     */
    public function index()
    {
        $teacher = Auth::user();

        // 1. Get all weekly slots for this teacher (with multi-student support)
        $weeklySlots = WeeklySlot::where('teacher_id', $teacher->id)
            ->with(['client', 'students'])
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_of_week');

        // 2. Get appointments logged THIS WEEK (for the "smart" button lookup)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $loggedAppointments = Appointment::where('teacher_id', $teacher->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->get();

        // 3. Lookup keyed by first student (or legacy client)
        $loggedSlotsLookup = [];
        foreach ($loggedAppointments as $appointment) {
            $dayOfWeek = $appointment->start_time->dayOfWeek;
            $startTime = $appointment->start_time->format('H:i:s');
            $clientId = $appointment->client_id;

            $key = "{$clientId}-{$dayOfWeek}-{$startTime}";
            $loggedSlotsLookup[$key] = true;
        }

        // Calendar events (multi-student sessions tinted yellow)
        $allSlots = $weeklySlots->flatten();
        $calendarEvents = $allSlots->map(function ($slot) {
            $studentNames = $slot->students->pluck('name');
            $clientName = $studentNames->isNotEmpty()
                ? $studentNames->implode(', ')
                : ($slot->client ? $slot->client->name : 'عميل غير محدد');

            $subject = $slot->teacher->subject ?? 'حصة';

            // Color logic: multi-student -> yellow, single -> indigo
            $color = $slot->students->count() > 1 ? '#f59e0b' : '#4f46e5';

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

        // Day names
        $daysOfWeek = [
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            0 => 'الأحد',
        ];

        // Assigned clients (filtered to active subscriptions)
        $assignedClients = $teacher->clients()->orderBy('name')->get();
        $clients = $assignedClients->filter(fn($client) => $client->hasActiveSubscription());

        return view('teacher.schedule.index', [
            'weeklySlots' => $weeklySlots,
            'daysOfWeek' => $daysOfWeek,
            'loggedSlotsLookup' => $loggedSlotsLookup,
            'calendarEvents' => json_encode($calendarEvents),
            'clients' => $clients,
        ]);
    }

    /**
     * Store a new weekly slot in the database (multi-student).
     */
    public function store(Request $request)
    {
        $teacher = Auth::user();

        $validated = $request->validate([
            'students' => ['required', 'array', 'min:1'],
            'students.*' => [
                'integer',
                Rule::in($teacher->clients()->pluck('users.id')), // ensure assigned to this teacher
            ],
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i', // HH:MM
        ]);

        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHour(); // 1 hour duration

        // --- NEW VALIDATION: Check Capacity ---
        $capacityError = $this->validateSlotCapacity($validated['students']);
        if ($capacityError) {
            return redirect()->back()->withErrors(['message' => $capacityError]);
        }
        // --------------------------------------

        // Overlap check (same teacher, same day, time collision)
        $isOverlap = WeeklySlot::where('teacher_id', $teacher->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime) {
                    $q->where('start_time', '<', $startTime->format('H:i:s'))
                        ->where('end_time', '>', $startTime->format('H:i:s'));
                })->orWhere(function ($q) use ($endTime) {
                    $q->where('start_time', '<', $endTime->format('H:i:s'))
                        ->where('end_time', '>', $endTime->format('H:i:s'));
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '>=', $startTime->format('H:i:s'))
                        ->where('end_time', '<=', $endTime->format('H:i:s'));
                });
            })->exists();

        if ($isOverlap) {
            return redirect()->back()->withErrors(['message' => 'هذا التوقيت يتعارض مع حصة أخرى.']);
        }

        // Create the slot (keep client_id for legacy: first selected student)
        $weeklySlot = WeeklySlot::create([
            'teacher_id' => $teacher->id,
            'client_id' => $validated['students'][0] ?? null,
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
        ]);

        // Attach multiple students
        $weeklySlot->students()->sync($validated['students']);

        return redirect()->back()->with('status', 'تمت إضافة الحصة الأسبوعية بنجاح.');
    }

    /**
     * Show edit form (students only).
     */
    public function edit(WeeklySlot $weeklySlot)
    {
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $teacher = Auth::user();

        // Eligible clients
        $assignedClients = $teacher->clients()->orderBy('name')->get();
        $clients = $assignedClients->filter(fn($client) => $client->hasActiveSubscription());

        $weeklySlot->load('students', 'client', 'teacher');

        return view('teacher.schedule.edit', [
            'weeklySlot' => $weeklySlot,
            'clients' => $clients,
        ]);
    }

    /**
     * Update students for a slot.
     */
    public function update(Request $request, WeeklySlot $weeklySlot)
    {
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $teacher = Auth::user();

        $validated = $request->validate([
            'students' => ['required', 'array', 'min:1'],
            'students.*' => [
                'integer',
                Rule::in($teacher->clients()->pluck('users.id')),
            ],
        ]);

        // --- NEW VALIDATION: Check Capacity ---
        $capacityError = $this->validateSlotCapacity($validated['students']);
        if ($capacityError) {
            return redirect()->back()->withErrors(['message' => $capacityError]);
        }
        // --------------------------------------

        $weeklySlot->students()->sync($validated['students']);
        $weeklySlot->client_id = $validated['students'][0] ?? null; // keep legacy compatibility
        $weeklySlot->save();

        return redirect()->route('teacher.schedule.index')->with('status', 'تم تحديث الحصة بنجاح.');
    }

    /**
     * Remove the specified weekly slot.
     */
    public function destroy(WeeklySlot $weeklySlot)
    {
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $weeklySlot->delete();
        return redirect()->back()->with('status', 'تم حذف الحصة الأسبوعية.');
    }


    /**
     * Helper to validate that the selected group size does not exceed
     * the limits of any individual student's subscription plan.
     *
     * @param array $studentIds
     * @return string|null Error message or null if valid
     */
    private function validateSlotCapacity(array $studentIds): ?string
    {
        $count = count($studentIds);

        // Optimally fetch students with their active subscription
        $students = \App\Models\User::whereIn('id', $studentIds)
            ->with([
                'subscriptions' => function ($q) {
                    $q->where('status', 'active')
                        ->where('ends_at', '>', Carbon::now())
                        ->whereColumn('lessons_used', '<', 'total_lessons');
                }
            ])
            ->get();

        foreach ($students as $student) {
            $sub = $student->subscriptions->first();

            // Default limit for safety (or legacy plans) -> 1 (Private)
            $limit = 1;

            if ($sub) {
                switch ($sub->plan_type) {
                    case 'normal':
                        $limit = 8;
                        break;
                    case 'vip':
                        $limit = 4;
                        break;
                    case 'duo':
                        $limit = 2;
                        break;
                    case 'one_to_one':
                        $limit = 1;
                        break;
                    default:
                        // Allow legacy 'advanced'/'intensive' to have larger groups?
                        // Let's assume legacy = 1 for safety unless specified, 
                        // but maybe 8 is safer to avoid breaking existing flows?
                        // Given the user prompt "one-to-one is just 1 student", 
                        // let's stick to 1 for unknown.
                        $limit = 1;
                        break;
                }
            }

            if ($count > $limit) {
                $planName = $sub->plan_type ?? 'غير محددة';
                return "لا يمكن إضافة {$count} طلاب. الطالب '{$student->name}' مشترك في باقة ({$planName}) والتي تسمح بـ {$limit} طلاب كحد أقصى في الحصة.";
            }
        }

        return null;
    }
}