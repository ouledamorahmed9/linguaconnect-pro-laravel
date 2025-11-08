<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WeeklySlot;
use App\Models\Appointment; // <-- ** STEP 1: Import Appointment **
use Illuminate\Http\Request;
use Carbon\Carbon; // <-- ** STEP 2: Import Carbon **

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
        $calendarEvents = []; // <-- ** STEP 1: Initialize Calendar Events Array **

        if ($selectedTeacherId) {
            $selectedTeacher = User::find($selectedTeacherId);

            if ($selectedTeacher && $selectedTeacher->hasRole('teacher')) {
                // Get clients assigned to this teacher
                $clients = $selectedTeacher->clients()->orderBy('name')->get();

                // Get all existing weekly slots for this teacher (for the LIST view)
                $weeklySlots = WeeklySlot::where('teacher_id', $selectedTeacher->id)
                                ->with('client')
                                ->get()
                                ->sortBy([
                                    ['client.name', 'asc'],
                                    ['day_of_week', 'asc'],
                                    ['start_time', 'asc'],
                                ])
                                ->groupBy('client.name');
                
                // Get teacher stats (existing logic)
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

                // --- ** STEP 2: NEW LOGIC FOR GRAPHICAL CALENDAR ** ---
                // We need all slots (not grouped) for the calendar
                $allSlots = WeeklySlot::where('teacher_id', $selectedTeacher->id)
                                      ->with('client', 'teacher')
                                      ->get();
                
                $calendarEvents = $allSlots->map(function ($slot) {
                    $clientName = $slot->client ? $slot->client->name : 'عميل غير محدد';
                    $subject = $slot->teacher->subject ?? 'حصة';
                    
                    return [
                        'id' => $slot->id,
                        'title' => "{$clientName} ({$subject})", // e.g., "Ahmed (French)"
                        'daysOfWeek' => [$slot->day_of_week], // Makes it repeating
                        'startTime' => $slot->start_time,
                        'endTime' => $slot->end_time,
                        'color' => '#4f46e5', // Professional Indigo color
                        'allDay' => false,
                        'clientName' => $clientName, // For tooltip
                        'subject' => $subject,     // For tooltip
                    ];
                });
                // --- ** END OF NEW LOGIC ** ---

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
            'teacherStats' => $teacherStats, // <-- ** STEP 5: Pass stats to view **
        ]);
    }

    /**
     * Store a new weekly slot in the database.
     * (This method is unchanged)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|integer|exists:users,id',
            'client_id' => 'required|integer|exists:users,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i', // Expects "HH:MM" format (e.g., 20:00)
        ]);
    
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHour(); // All lessons are 1 hour
    
        // Professional Check: Does this slot overlap with another?
        $isOverlap = WeeklySlot::where('teacher_id', $validated['teacher_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($startTime, $endTime) {
                $query->where(function($q) use ($startTime) {
                    // New slot starts during an existing slot
                    $q->where('start_time', '<', $startTime->format('H:i:s'))
                      ->where('end_time', '>', $startTime->format('H:i:s'));
                })->orWhere(function($q) use ($endTime) {
                    // New slot ends during an existing slot
                    $q->where('start_time', '<', $endTime->format('H:i:s'))
                      ->where('end_time', '>', $endTime->format('H:i:s'));
                })->orWhere(function($q) use ($startTime, $endTime) {
                    // New slot surrounds an existing slot
                    $q->where('start_time', '>=', $startTime->format('H:i:s'))
                      ->where('end_time', '<=', $endTime->format('H:i:s'));
                });
            })->exists();
    
        if ($isOverlap) {
            return redirect()->back()->withErrors(['message' => 'This time slot overlaps with an existing weekly slot for this teacher.']);
        }
    
        // Create the new slot
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
     * Remove the specified weekly slot from storage.
     * (This method is unchanged)
     */
    public function destroy(WeeklySlot $weeklySlot)
    {
        // You can add authorization here if needed
        $weeklySlot->delete();
        return redirect()->back()->with('status', 'تم حذف الحصة الأسبوعية.');
    }
}