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

        // 1. Get Schedule Data (Existing code)
        $weeklySlots = WeeklySlot::where('teacher_id', $teacher->id)
            ->with(['client', 'students'])
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_of_week');

        // 2. Get Appointments (Existing code)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $loggedAppointments = Appointment::where('teacher_id', $teacher->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->get();

        // 3. Build Lookup Array (Existing code)
        $loggedSlotsLookup = [];
        foreach ($loggedAppointments as $appointment) {
            $key = "{$appointment->client_id}-{$appointment->start_time->dayOfWeek}-{$appointment->start_time->format('H:i:s')}";
            $loggedSlotsLookup[$key] = true;
        }

        // 4. Calendar Events (Existing code)
        $allSlots = $weeklySlots->flatten();
        $calendarEvents = $allSlots->map(function ($slot) {
            $studentNames = $slot->students->pluck('name');
            $clientName = $studentNames->isNotEmpty() 
                ? $studentNames->implode(', ') 
                : ($slot->client ? $slot->client->name : 'عميل غير محدد');
                
            return [
                'id' => $slot->id,
                'title' => "{$clientName}", // Simplified title
                'daysOfWeek' => [$slot->day_of_week],
                'startTime' => $slot->start_time,
                'endTime' => $slot->end_time,
                'color' => $slot->students->count() > 1 ? '#f59e0b' : '#4f46e5',
            ];
        });

        // 5. FETCH THE CLIENTS FOR THE DROPDOWN
        // This is the critical part. We fetch clients LINKED to this teacher via the pivot table.
        // We filter to ensure they still have an active subscription date.
        $clients = $teacher->clients()
            ->whereHas('subscriptions', function ($query) {
                $query->where('status', 'active')
                      ->where('ends_at', '>', now());
            })
            ->orderBy('name')
            ->get();

        $daysOfWeek = [
            1 => 'الاثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 
            4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت', 0 => 'الأحد',
        ];

        return view('teacher.schedule.index', [
            'weeklySlots' => $weeklySlots,
            'daysOfWeek' => $daysOfWeek,
            'loggedSlotsLookup' => $loggedSlotsLookup,
            'calendarEvents' => json_encode($calendarEvents),
            'clients' => $clients, // This variable now contains the linked students
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
        // Update edit filter as well to match index logic if needed, 
        // otherwise keep your original filter if that was preferred here.
        // For consistency, I will use the hasActiveSubscription helper if it's reliable,
        // or the same query as index. Let's stick to your original code here to minimize changes as requested:
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