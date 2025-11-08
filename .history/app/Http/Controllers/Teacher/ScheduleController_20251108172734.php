<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WeeklySlot;
use App\Models\Appointment; 
use App\Models\User; // <-- ** Import User model **
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

        // Get all weekly slots (we need all, even inactive, to show them in red)
        $allSlots = WeeklySlot::where('teacher_id', $teacher->id)
                              ->with('client.subscriptions', 'teacher') // Eager load for performance
                              ->get();

        // ** MODIFICATION 1: Check subscription status for each slot **
        $allSlots->each(function($slot) {
            // Check if client exists before checking subscription
            $slot->hasActiveSubscription = $slot->client ? $slot->client->hasActiveSubscription() : false;
        });
        
        // Group for the LIST view
        $weeklySlots = $allSlots->sortBy([
                                    ['client.name', 'asc'],
                                    ['day_of_week', 'asc'],
                                    ['start_time', 'asc'],
                                ])
                                ->groupBy('day_of_week'); // Group by day for the list

        // Get appointments logged THIS WEEK (for 'smart' button)
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
        
        // ** MODIFICATION 2: Set calendar color based on subscription status **
        $calendarEvents = $allSlots->map(function ($slot) {
            $clientName = $slot->client ? $slot->client->name : 'عميل غير محدد';
            $subject = $slot->teacher->subject ?? 'حصة';
            
            return [
                'id' => $slot->id,
                'title' => "{$clientName} ({$subject})", 
                'daysOfWeek' => [$slot->day_of_week], 
                'startTime' => $slot->start_time,
                'endTime' => $slot->end_time,
                'color' => $slot->hasActiveSubscription ? '#4f46e5' : '#dc2626', // Indigo if active, Red if not
                'allDay' => false,
                'clientName' => $clientName, 
                'subject' => $subject,
                'hasActiveSubscription' => $slot->hasActiveSubscription, // Pass status to tooltip
            ];
        });

        // Helper array for day names (as before)
        $daysOfWeek = [ 1 => 'الاثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت', 0 => 'الأحد' ];
        
        // ** MODIFICATION 3: Get only clients with active subscriptions for the dropdown **
        $clients = $teacher->clients()
                           ->whereHas('subscriptions', function ($query) {
                                $query->where('status', 'active')
                                      ->where('ends_at', '>', now())
                                      ->whereColumn('lessons_used', '<', 'total_lessons');
                           })
                           ->orderBy('name')
                           ->get();

        return view('teacher.schedule.index', [
            'weeklySlots' => $weeklySlots,
            'daysOfWeek' => $daysOfWeek,
            'loggedSlotsLookup' => $loggedSlotsLookup, 
            'calendarEvents' => json_encode($calendarEvents),
            'clients' => $clients, // Pass this new filtered list
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
            'start_time' => 'required|date_format:H:i',
        ]);

        // --- ** NEW PROFESSIONAL CHECK ** ---
        // Block creating a slot for a client with no active subscription
        $client = User::find($validated['client_id']);
        if (!$client || !$client->hasActiveSubscription()) {
             return redirect()->back()->withInput()->withErrors(['client_id' => 'لا يمكن إضافة حصة لعميل ليس لديه اشتراك نشط.']);
        }
        // --- ** END OF CHECK ** ---
    
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHour();
    
        $isOverlap = WeeklySlot::where('teacher_id', $teacher->id)
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
    
        WeeklySlot::create([
            'teacher_id' => $teacher->id,
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
        if ($weeklySlot->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $weeklySlot->delete();
        return redirect()->back()->with('status', 'تم حذف الحصة الأسبوعية.');
    }
}