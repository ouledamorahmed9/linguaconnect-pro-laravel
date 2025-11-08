<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WeeklySlot;
use App\Models\Appointment; // <-- ** Import Appointment **
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'teacherStats' => $teacherStats,
            'calendarEvents' => json_encode($calendarEvents), // <-- ** STEP 3: Pass new data **
        ]);
    }

    /**
     * Store a new weekly slot in the database.
     * (This method is unchanged)
     */
    public function store(Request $request)
    {
// ... (existing code is unchanged)
        $validated = $request->validate([
// ... (existing code is unchanged)
        ]);
// ... (existing code is unchanged)
        $startTime = Carbon::parse($validated['start_time']);
// ... (existing code is unchanged)
        $isOverlap = WeeklySlot::where('teacher_id', $validated['teacher_id'])
// ... (existing code is unchanged)
                })->exists();
    
        if ($isOverlap) {
// ... (existing code is unchanged)
        }
    
        // Create the new slot
        WeeklySlot::create([
// ... (existing code is unchanged)
        ]);
    
        return redirect()->back()->with('status', 'تمت إضافة الحصة الأسبوعية بنجاح.');
    }

    /**
     * Remove the specified weekly slot from storage.
     * (This method is unchanged)
     */
    public function destroy(WeeklySlot $weeklySlot)
    {
// ... (existing code is unchanged)
        $weeklySlot->delete();
        return redirect()->back()->with('status', 'تم حذف الحصة الأسبوعية.');
    }
}