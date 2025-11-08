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
     * Display the teacher's new weekly schedule (roster).
     */
    public function index()
    {
        $teacher = Auth::user();

        // 1. Get all weekly slots for this teacher (for the LIST view)
        $weeklySlots = WeeklySlot::where('teacher_id', $teacher->id)
            ->with('client') 
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_of_week');

        // 2. Get appointments logged THIS WEEK (for the LIST view 'smart' button)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $loggedAppointments = Appointment::where('teacher_id', $teacher->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->get();

        // 3. Create a fast lookup array for the view
        $loggedSlotsLookup = [];
        foreach ($loggedAppointments as $appointment) {
            $dayOfWeek = $appointment->start_time->dayOfWeek; 
            $startTime = $appointment->start_time->format('H:i:s');
            $clientId = $appointment->client_id;
            
            $key = "{$clientId}-{$dayOfWeek}-{$startTime}";
            $loggedSlotsLookup[$key] = true;
        }
        
        // --- Logic for Graphical Calendar (existing) ---
        $allSlots = $weeklySlots->flatten(); // Use the grouped collection
        
        $calendarEvents = $allSlots->map(function ($slot) {
            $clientName = $slot->client ? $slot->client->name : 'عميل غير محدد';
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
        // --- End of Calendar Logic ---

        // 4. Helper array for day names (as before)
        $daysOfWeek = [
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            0 => 'الأحد',
        ];
        
        // ** STEP 2: Get clients assigned to this teacher (for the new form dropdown) **
        // --- ** THIS IS THE MODIFICATION ** ---
        // Get all assigned clients, then filter them in PHP 
        // using the new professional method from the User model.
        $assignedClients = $teacher->clients()->orderBy('name')->get();
        
        $clients = $assignedClients->filter(function ($client) {
            return $client->hasActiveSubscription();
        });
        // --- ** END OF MODIFICATION ** ---

        return view('teacher.schedule.index', [
            'weeklySlots' => $weeklySlots,
            'daysOfWeek' => $daysOfWeek,
            'loggedSlotsLookup' => $loggedSlotsLookup, 
            'calendarEvents' => json_encode($calendarEvents),
            'clients' => $clients, // <-- ** Passed the *filtered* clients **
        ]);
    }

    /**
     * Store a new weekly slot in the database.
     */
    public function store(Request $request)
    {
        $teacher = Auth::user();

        $validated = $request->validate([
            // Security Check: Client must be one of the teacher's assigned clients
            'client_id' => [
                'required',
                'integer',
                Rule::in($teacher->clients()->pluck('users.id'))
            ],
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i', // Expects "HH:MM" format
        ]);
    
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHour(); // All lessons are 1 hour
    
        // Professional Check: Does this slot overlap?
        $isOverlap = WeeklySlot::where('teacher_id', $teacher->id) // Only check this teacher's slots
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
            return redirect()->back()->withErrors(['message' => 'This time slot overlaps with an existing weekly slot.']);
        }
    
        // Create the new slot
        WeeklySlot::create([
            'teacher_id' => $teacher->id, // Use authenticated teacher's ID
            'client_id' => $validated['client_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
        ]);
    
        return redirect()->back()->with('status', 'تمت إضافة الحصة الأسبوعية بنجاح.');
    }

    /**
     * Remove the specified weekly slot from storage.
     */
    public function destroy(WeeklySlot $weeklySlot)
    {
        // ** CRITICAL SECURITY CHECK **
        // Ensure the teacher deleting the slot is the one who owns it
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $weeklySlot->delete();
        return redirect()->back()->with('status', 'تم حذف الحصة الأسبوعية.');
    }
}