<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WeeklySlot;
use App\Models\Appointment;
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
        $calendarEvents = [];

        if ($selectedTeacherId) {
            $selectedTeacher = User::find($selectedTeacherId);

            if ($selectedTeacher && $selectedTeacher->hasRole('teacher')) {
                
                // --- ** THIS IS THE FIX (Replaces the buggy code) ** ---
                // 1. Get ALL assigned clients (this is a simple, bug-free query)
                $allClients = $selectedTeacher->clients()->with('subscriptions')->orderBy('name')->get();
                
                // 2. Filter them in PHP to get ONLY active clients for the dropdown
                //    This uses the 'hasActiveSubscription' function we built.
                $clients = $allClients->filter(function ($client) {
                    return $client->hasActiveSubscription();
                });
                // --- ** END OF FIX ** ---
                
                // Get all weekly slots (we need all, even inactive, to show them in red)
                $allSlots = WeeklySlot::where('teacher_id', $selectedTeacher->id)
                                      ->with('client.subscriptions', 'teacher') // Eager load for performance
                                      ->get();

                // Check subscription status for each slot
                $allSlots->each(function($slot) {
                    $slot->hasActiveSubscription = $slot->client ? $slot->client->hasActiveSubscription() : false;
                });

                // Group for the LIST view
                $weeklySlots = $allSlots->sortBy([
                                    ['client.name', 'asc'],
                                    ['day_of_week', 'asc'],
                                    ['start_time', 'asc'],
                                ])
                                ->groupBy('client.name');
                
                // Get teacher stats (existing logic)
                $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
                $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
                $teacherStats = [
                    'verified' => Appointment::where('teacher_id', $selectedTeacher->id)->where('status', 'verified')->count(),
                    'pending' => Appointment::where('teacher_id', $selectedTeacher->id)->where('status', 'pending_verification')->count(),
                    'this_week' => Appointment::where('teacher_id', $selectedTeacher->id)
                                    ->whereIn('status', ['verified', 'pending_verification'])
                                    ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                                    ->count(),
                ];

                // Set calendar color based on subscription status
                $calendarEvents = $allSlots->map(function ($slot) {
                    $clientName = $slot->client ? $slot->client->name : 'عميل محذوف';
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
                        'hasActiveSubscription' => $slot->hasActiveSubscription,
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
            'clients' => $clients, // This is now a filtered list of ACTIVE clients
            'weeklySlots' => $weeklySlots, // This list includes INACTIVE slots
            'teacherStats' => $teacherStats,
            'calendarEvents' => json_encode($calendarEvents),
        ]);
    }

    /**
     * Store a new weekly slot in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|integer|exists:users,id',
            'client_id' => 'required|integer|exists:users,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
        ]);
        
        // Block creating a slot for a client with no active subscription
        $client = User::find($validated['client_id']);
        if (!$client || !$client->hasActiveSubscription()) {
             return redirect()->back()->withInput()->withErrors(['client_id' => 'لا يمكن إضافة حصة لعميل ليس لديه اشتراك نشط.']);
        }

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
            return redirect()->back()->withErrors(['message' => 'This time slot overlaps with an existing weekly slot for this teacher.']);
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
     * Remove the specified weekly slot from storage.
     */
    public function destroy(WeeklySlot $weeklySlot)
    {
        $weeklySlot->delete();
        return redirect()->back()->with('status', 'تم حذف الحصة الأسبوعية.');
    }
}