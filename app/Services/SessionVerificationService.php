<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Subscription;

class SessionVerificationService
{
    /**
     * Verify a session and decrement the client's lesson credits.
     * This is the central business logic for session verification.
     *
     * @param Appointment $appointment
     * @return array [bool $success, string $message]
     */
    public function verifySession(Appointment $appointment): array
    {
        // 1. Find the client's active subscription that covers this appointment's date.
        $activeSubscription = Subscription::where('user_id', $appointment->client_id)
            ->where('status', 'active')
            ->where('starts_at', '<=', $appointment->start_time)
            ->where('ends_at', '>=', $appointment->start_time)
            ->first();

        // 2. Check for potential issues
        if (!$activeSubscription) {
            return [false, 'Verification failed: No active subscription found for this client on this date.'];
        }

        if ($activeSubscription->lessons_used >= $activeSubscription->total_lessons) {
            return [false, 'Verification failed: Client has no remaining lesson credits.'];
        }

        // 3. All checks passed. Proceed with the professional "transaction".
        $activeSubscription->increment('lessons_used');
        
        return [true, 'Session verified and lesson credit used successfully!'];
    }
}

