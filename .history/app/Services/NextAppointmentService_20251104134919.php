<?php

namespace App\Services;

use App\Models\User;
use App\Models\Appointment;
use Carbon\Carbon;

class NextAppointmentService
{
    public function getNextAppointmentForClient(User $client)
    {
        return Appointment::with('teacher')
            ->where('client_id', $client->id)
            ->where('start_time', '>', Carbon::now())
            ->where('status', 'scheduled')
            ->orderBy('start_time', 'asc')
            ->first();
    }

    /**
     * --- THIS IS THE NEW METHOD THAT FIXES THE ERROR ---
     * * Get the next upcoming scheduled appointment for a teacher.
     *
     * @param User $teacher
     * @return Appointment|null
     */
    public function getNextAppointmentForTeacher(User $teacher)
    {
        return Appointment::with('client') // Eager load the client
            ->where('teacher_id', $teacher->id)
            ->where('start_time', '>', Carbon::now()) // Only find appointments in the future
            ->where('status', 'scheduled') // Only find scheduled appointments
            ->orderBy('start_time', 'asc') // Get the very next one
            ->first();
    }
}
