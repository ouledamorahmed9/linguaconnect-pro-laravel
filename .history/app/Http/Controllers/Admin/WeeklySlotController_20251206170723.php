<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WeeklySlot;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class WeeklySlotController extends Controller
{
    /**
     * Display the weekly roster management page.
     */
    public function index(Request $request)
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $selectedTeacherId = $request->input('teacher_id');
        $selectedTeacher = null;
        $clients = collect();
        $weeklySlots = collect();
        $teacherStats = null;
        $calendarEvents = []; 

        if ($selectedTeacherId) {
            $selectedTeacher = User::find($selectedTeacherId);

            if ($selectedTeacher && $selectedTeacher->hasRole('teacher')) {
                // Assigned clients (active)
                $assignedClients = $selectedTeacher->clients()->orderBy('name')->get();
                $clients = $assignedClients->filter(fn ($client) => $client->hasActiveSubscription());

                // Existing weekly slots
                $weeklySlots = WeeklySlot::where('teacher_id', $selectedTeacher->id)
                                ->with(['client', 'students', 'teacher'])
                                ->get()
                                ->sortBy([
                                    ['client.name', 'asc'],
                                    ['day_of_week', 'asc'],
                                    ['start_time', 'asc'],
                                ])
                                ->groupBy('client.name');

                // Teacher stats
                $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
                $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
                $teacherStats = [
                    'verified' => Appointment::where('teacher_id', $selectedTeacher->id)
                                    ->where('status', 'verified')
                                    ->count(),
                    'pending' => Appointment::where('teacher_id', $selectedTeacher->id)
                                    ->where('status', 'pending_verification')
                                    ->count(),
                    'this_week' => Appointment::where('teacher_id', $selectedTeacher->id)
                                    ->whereIn('status', ['verified', 'pending_verification'])
                                    ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                                    ->count(),
                ];

                // Calendar events (multi-student => yellow)
                $allSlots = WeeklySlot::where('teacher_id', $selectedTeacher->id)
                                      ->with(['client', 'teacher', 'students'])
                                      ->get();
                
                $calendarEvents = $allSlots->map(function ($slot) {
                    $names = $slot->students->pluck('name');
                    $clientName = $names->isNotEmpty()
                        ? $names->implode(', ')
                        : ($slot->client ? $slot->client->name : 'عميل غير محدد');
                    $subject = $slot->teacher->subject ?? 'حصة';

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

            } else {
                $selectedTeacherId = null;
            }
        }

        return view('admin.roster.index', [
            'teachers' => $teachers,
            'selectedTeacherId' => $selectedTeacherId,
            'selectedTeacher' => $selectedTeacher,
            'clients' => $clients,
            'weeklySlots' => $weeklySlots,
            'teacherStats' => $teacherStats,
            'calendarEvents' => json_encode($calendarEvents),
        ]);
    }

    /**
     * Store a new weekly slot in the database (multi-student).
     */
    public function store(Request $request)
    {
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
            return redirect()->back()->withErrors(['message' => 'هذا التوقيت يتعارض مع حصة أخرى لهذا المعلم.']);
        }
    
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
     * Edit students in a weekly slot.
     */
    public function edit(WeeklySlot $weeklySlot)
    {
        $teacher = $weeklySlot->teacher;
        $assignedClients = $teacher->clients()->orderBy('name')->get();
        $clients = $assignedClients->filter(fn ($client) => $client->hasActiveSubscription());
        $weeklySlot->load('students', 'client', 'teacher');

        return view('admin.roster.edit', [
            'weeklySlot' => $weeklySlot,
            'clients' => $clients,
        ]);
    }

    /**
     * Update students in a weekly slot.
     */
    public function update(Request $request, WeeklySlot $weeklySlot)
    {
        $teacher = $weeklySlot->teacher;
        $allowed = $teacher->clients()->pluck('users.id');

        $validated = $request->validate([
            'students' => ['required', 'array', 'min:1'],
            'students.*' => [
                'integer',
                Rule::in($allowed),
            ],
        ]);

        $weeklySlot->students()->sync($validated['students']);
        $weeklySlot->client_id = $validated['students'][0] ?? null; // keep legacy compatibility
        $weeklySlot->save();

        return redirect()->route('admin.roster.index', ['teacher_id' => $teacher->id])
                         ->with('status', 'تم تحديث الحصة بنجاح.');
    }

    /**
     * Remove the specified weekly slot from storage.
     */
    public function destroy(WeeklySlot $weeklySlot)
    {
        $weeklySlot->delete();
        return redirect()->back()->with('status', 'تم حذف الحصة الأسبوعية.');
    }
}