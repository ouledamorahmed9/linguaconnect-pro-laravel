<?php

    namespace App\Http\Controllers\Teacher;
    
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Appointment; // We use the Appointment (log) model
    
    class LessonHistoryController extends Controller
    {
        /**
         * Display a paginated list of the teacher's lesson history.
         */
        public function index()
        {
            $teacher = Auth::user();
    
            // Fetch all logged appointments for this teacher
            $lessons = Appointment::where('teacher_id', $teacher->id)
                                ->with('client') // Eager load the client's name
                                ->orderBy('start_time', 'desc') // Show newest lessons first
                                ->paginate(10); // Paginate the results
    
            return view('teacher.history.index', [
                'lessons' => $lessons,
            ]);
        }
    }