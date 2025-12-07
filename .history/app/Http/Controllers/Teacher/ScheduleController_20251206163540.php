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

        // Weekly slots with multi-student support
        $weeklySlots = WeeklySlot::where('teacher_id', $teacher->id)
            ->with(['client', 'students'])
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_of_week');

        // Logged appointments lookup (this week)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $loggedAppointments = Appointment::where('teacher_id', $teacher->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->get();

        $loggedSlotsLookup = [];
        foreach ($loggedAppointments as $appointment) {
            $dayOfWeek = $appointment->start_time->dayOfWeek;
            $startTime = $appointment->start_time->format('H:i:s');
            $clientId = $appointment->client_id;
            $key = "{$clientId}-{$dayOfWeek}-{$startTime}";
            $loggedSlotsLookup[$key] = true;
        }

        // Calendar events
        $allSlots = $weeklySlots->flatten();
        $calendarEvents = $allSlots->map(function ($slot) {
            $studentNames = $slot->students->pluck('name');
            $clientName = $studentNames->isNotEmpty()
                ? $studentNames->implode(', ')
                : ($slot->client ? $slot->client->name : 'عميل غير محدد');

            $subject = $slot->teacher->subject ?? 'حصة';

            return [
                'id' => $slot->id,
                'title' => "{$clientName} ({$subject})",
                'daysOfWeek' => [$slot->day_of_week],
                'startTime' => $slot->start_time,
                'endTime' => $slot->end_time,
                'color' => '#4f46e5',
                'allDay' => false,
                'clientName' => $clientName,
                'subject' => $subject,
            ];
        });

        $daysOfWeek = [
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            0 => 'الأحد',
        ];

        // Eligible clients (active subscriptions)
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
     * Store a new weekly slot (multi-student).
     */
    public function store(Request $request)
    {
        $teacher = Auth::user();

        $validated = $request->validate([
            'students' => ['required', 'array', 'min:1'],
            'students.*' => [
                'integer',
                Rule::in($teacher->clients()->pluck('users.id')),
            ],
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
        ]);

        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHour();

        // Overlap check
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

        $weeklySlot = WeeklySlot::create([
            'teacher_id' => $teacher->id,
            'client_id' => $validated['students'][0] ?? null,
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
        ]);

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
}