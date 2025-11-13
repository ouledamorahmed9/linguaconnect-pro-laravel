<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\MeetReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\SessionVerificationService; // We need this

class MeetReportController extends Controller
{
    /**
     * Store a new meet report and attempt to auto-verify the appointment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'meetingCode' => 'required|string',
            'meetingUrl' => 'required|url',
            'meetingData' => 'required|array',
        ]);

        $user = Auth::user();
        $meetListCode = $request->input('meetingCode'); // e.g., "meeting_zoa-ehvk-iza_1761827882304"
        $meetingData = $request->input('meetingData');

        // --- SMART PARSING LOGIC ---
        // Extract "zoa-ehvk-iza" from "meeting_zoa-ehvk-iza_1761827882304"
        $meetCode = null;
        $parts = explode('_', $meetListCode);
        if (count($parts) === 3 && $parts[0] === 'meeting') {
            $meetCode = $parts[1]; // This is "zoa-ehvk-iza"
        }

        if (!$meetCode) {
            Log::error('Could not parse Google Meet code from MeetList code: ' . $meetListCode);
            return response()->json([
                'success' => false,
                'message' => 'Error: Could not parse Google Meet code.'
            ], 400);
        }
        // --- END PARSING ---


        // 1. Find the matching appointment in the database
        $appointment = Appointment::where('teacher_id', $user->id)
            ->where('google_meet_link', 'LIKE', '%' . $meetCode . '%')
            ->whereIn('status', ['pending', 'confirmed', 'pending_verification']) // Added 'pending_verification'
            ->first();

        if (!$appointment) {
            Log::warning('Meet report sync failed: No matching & pending/confirmed appointment found for teacher ' . $user->id . ' with code ' . $meetCode);
            return response()->json([
                'success' => false,
                'message' => 'Error: No matching & pending/confirmed appointment found for this Google Meet code: ' . $meetCode
            ], 404);
        }

        // 2. Save the report (or find it if it was already synced)
        // This prevents the "Duplicate entry" error
        $report = MeetReport::firstOrCreate(
            ['meeting_code' => $meetListCode], // Find by this
            [ // Or Create with this data
                'appointment_id' => $appointment->id,
                'meeting_url'    => $request->input('meetingUrl'),
                'report_data'    => $meetingData,
            ]
        );

        // If the report was not just created, it means it was a duplicate sync.
        if (!$report->wasRecentlyCreated) {
            return response()->json([
                'success' => true, // It's not an error
                'message' => 'This report has already been synced.'
            ]);
        }

        // 3. **AUTOMATION LOGIC (THE "COMPARE")**
        $teacherName = $user->name;
        $teacherAttended = false;

        // Loop through the scraped participant list
        foreach ($meetingData['participants'] as $participant) {
            // Use str_contains for a flexible match (e.g., "Helmi Ahmed" matches "Helmi Ahmed")
            if (str_contains(strtolower($participant['name']), strtolower($teacherName))) {
                $teacherAttended = true;
                break; // Found them, no need to keep looping
            }
        }

        // 4. Update the appointment status based on the result
        if ($teacherAttended) {
            // SUCCESS! Teacher was in the call.
            // We use your existing service to make sure all logic is the same.
            $verificationService = new SessionVerificationService();
            $verificationService->verifySession($appointment); // This sets status to 'verified'

            // We can also update the session_proof to show it was auto-verified
            $appointment->session_proof = "Auto-verified by MeetReport ID: " . $report->id;
            $appointment->save();

            Log::info('Appointment ' . $appointment->id . ' auto-verified by MeetReport.');
            
            return response()->json([
                'success' => true,
                'message' => 'Sync successful! Session has been auto-verified.'
            ]);
        
        } else {
            // --- !! YOUR NEW LOGIC !! ---
            // Teacher's name not found in the log. Mark as CANCELLED.
            $appointment->status = 'cancelled'; // CHANGED from 'conflict'
            $appointment->session_proof = "Auto-synced report. Teacher name not found in participant log. Session marked cancelled.";
            $appointment->save();

            Log::warning('MeetReport ' . $report->id . ' synced, but teacher "' . $teacherName . '" not found in participant list. Marked as CANCELLED.');
            
            return response()->json([
                'success' => true,
                'message' => 'Sync successful! Session marked as CANCELLED (Teacher not found in log).' // CHANGED
            ]);
            // --- !! END OF NEW LOGIC !! ---
        }
    }
}