    <?php

    namespace App\Http\Controllers\Admin;
    
    use App\Http\Controllers\Controller;
    use App\Models\User;
    use App\Models\WeeklySlot;
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
    
            if ($selectedTeacherId) {
                $selectedTeacher = User::find($selectedTeacherId);
    
                if ($selectedTeacher && $selectedTeacher->hasRole('teacher')) {
                    // Get clients assigned to this teacher
                    $clients = $selectedTeacher->clients()->orderBy('name')->get();
    
                    // Get all existing weekly slots for this teacher
                    $weeklySlots = WeeklySlot::where('teacher_id', $selectedTeacher->id)
                                    ->with('client')
                                    ->get()
                                    // Group by the client's name for a clean display
                                    ->sortBy([
                                        ['client.name', 'asc'],
                                        ['day_of_week', 'asc'],
                                        ['start_time', 'asc'],
                                    ])
                                    ->groupBy('client.name');
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
         */
        public function destroy(WeeklySlot $weeklySlot)
        {
            // You can add authorization here if needed
            $weeklySlot->delete();
            return redirect()->back()->with('status', 'تم حذف الحصة الأسبوعية.');
        }
    }
    
