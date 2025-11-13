<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\MeetReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // For debugging

class MeetReportController extends Controller
{
    /**
     * Store a new MeetReport and auto-verify the appointment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'meetingCode' => 'required|string', // This is the MeetList code: "meeting_abc-def-ghi_12345"
            'meetingUrl' => 'required|url',
            'meetingData' => 'required|array',
        ]);

        $user = Auth::user(); // This is the authenticated Teacher
        $meetListCode = $request->input('meetingCode');
        $meetingData = $request->input('meetingData', []);

        // --- !! THIS IS THE FIX !! ---
        // 1. Extract the *real* Google Meet code from the MeetList code.
        // e.g., "meeting_sxx-jxui-jwx_1722417244223" -> "sxx-jxui-jwx"
        $googleMeetCode = null;
        if (preg_match('/^meeting_(.*)_(\d+)$/', $meetListCode, $matches)) {
            $googleMeetCode = $matches[1]; // This will be "sxx-jxui-jwx"
        } else {
            // Fallback for any other format
            $googleMeetCode = str_replace('meeting_', '', $meetListCode);
            Log::warning('Unusual MeetList code format: ' . $meetListCode);
        }

        if (empty($googleMeetCode)) {
             return response()->json([
                'success' => false,
                'message' => 'Could not parse the Google Meet code from the URL.'
            ], 400); // 400 Bad Request
        }
        // --- !! END OF FIX !! ---


        // 2. Find the appointment in your database using the *real* code
        // We look for an appointment for this teacher, that matches the Meet code,
        // and is in a state that is waiting for verification.
        $appointment = Appointment::where('teacher_id', $user->id)
            ->where('google_meet_link', 'LIKE', '%' . $googleMeetCode . '%') // <-- Use the extracted code
            ->whereIn('status', ['pending', 'confirmed', 'pending_verification']) // <-- Find appointments that are not yet completed
            ->first();

        if (!$appointment) {
            Log::warning('Meet report sync failed: No matching appointment found for teacher ' . $user->id . ' with Google Meet code ' . $googleMeetCode);
            return response()->json([
                'success' => false,
                'message' => 'No matching & pending/confirmed appointment found for this Google Meet code: ' . $googleMeetCode
            ], 404);
        }

        // 3. Save the report data
        $report = MeetReport::create([
            'appointment_id' => $appointment->id,
            'meeting_code'   => $meetListCode, // Save the original MeetList code
            'meeting_url'    => $request->input('meetingUrl'),
            'report_data'    => $meetingData,
        ]);

        // 4. **AUTOMATION LOGIC**
        // Now we auto-verify the session
        $teacherName = $user->name;
        $teacherAttended = false;

        foreach ($meetingData['participants'] ?? [] as $participant) {
            // Use case-insensitive comparison to find the teacher
            if (stripos($participant['name'], $teacherName) !== false) {
                $teacherAttended = true;
                break;
            }
        }

        if ($teacherAttended) {
            // Teacher was found in the log! Auto-verify.
            $appointment->status = 'verified'; // This is your "Auto-Verified" status
            $appointment->session_proof = "Auto-verified by MeetReport ID: " . $report->id;
            $appointment->save();

            Log::info('Appointment ' . $appointment->id . ' auto-verified by MeetReport.');
            
            return response()->json([
                'success' => true,
                'message' => 'Sync successful! Session has been auto-verified.'
            ]);

        } else {
            // Teacher was NOT found. Mark for admin review.
            $appointment->status = 'conflict';
            $appointment->session_proof = "Report synced. Teacher not found in log. Admin review needed.";
            $appointment->save();

            Log::warning('Appointment ' . $appointment->id . ' synced but teacher name not found. Marked as conflict.');

            return response()->json([
                'success' => true,
                'message' => 'Sync successful! Marked for Admin review (Teacher not found in log).'
            ]);
        }
    }
}