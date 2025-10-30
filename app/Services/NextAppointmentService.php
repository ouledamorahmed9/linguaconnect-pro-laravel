<?php

    namespace App\Services;

    use App\Models\Appointment;
    use Illuminate\Support\Facades\Auth;

    class NextAppointmentService
    {
        public function getNext()
        {
            if (!Auth::check()) {
                return null;
            }

            return Appointment::with('teacher')
                ->where('client_id', Auth::id())
                ->where('start_time', '>=', now())
                ->orderBy('start_time', 'asc')
                ->first();
        }
    }
    
