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
            'meetingCode' => 'required|string', // This is the MeetList code: "meeting_zoa-ehvk-iza_1761827882304"
            'meetingUrl' => 'required|url',
            'meetingData' => 'required|array',
        ]);

        $user = Auth::user(); // This is the authenticated Teacher
        $meetListCode = $request->input('meetingCode');
        $meetingData = $request->input('meetingData', []);

        // --- 1. Extract the real Google Meet code ---
        $googleMeetCode = null;
        if (preg_match('/^meeting_(.*)_(\d+)$/', $meetListCode, $matches)) {
            $googleMeetCode = $matches[1]; // This will be "zoa-ehvk-iza"
        } else {
            $googleMeetCode = str_replace('meeting_', '', $meetListCode);
            Log::warning('Unusual MeetList code format: ' . $meetListCode);
        }

        if (empty($googleMeetCode)) {
             return response()->json([
                'success' => false,
                'message' => 'Could not parse the Google Meet code from the URL.'
            ], 400); // 400 Bad Request
        }
        
        // --- 2. Find the matching appointment ---
        $appointment = Appointment::where('teacher_id', $user->id)
            ->where('google_meet_link', 'LIKE', '%' . $googleMeetCode . '%') 
            ->whereIn('status', ['pending', 'confirmed', 'pending_verification'])
            ->first();

        if (!$appointment) {
            Log::warning('Meet report sync failed: No matching appointment found for teacher ' . $user->id . ' with Google Meet code ' . $googleMeetCode);
            return response()->json([
                'success' => false,
                'message' => 'No matching & pending/confirmed appointment found for this Google Meet code: ' . $googleMeetCode
            ], 404);
        }

        // --- 3. Use firstOrCreate to prevent duplicate entries ---
        $report = MeetReport::firstOrCreate(
            [
                'meeting_code' => $meetListCode // 1. Try to find a report with this unique code
            ],
            [
                'appointment_id' => $appointment->id, // 2. If not found, create it with this data
                'meeting_url'    => $request->input('meetingUrl'),
                'report_data'    => $meetingData,
            ]
        );


        // 4. **AUTOMATION LOGIC**
        if (!$report->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'This report has already been synced.'
            ]);
        }

        // If we are here, the report is NEW. Let's process the appointment.
        $teacherName = $user->name;
        $teacherAttended = false;

        foreach ($meetingData['participants'] ?? [] as $participant) {
            if (stripos($participant['name'], $teacherName) !== false) {
                $teacherAttended = true;
                break;
            }
        }

        if ($teacherAttended) {
            // Teacher was found! Auto-verify.
            
            // --- !! BUG FIX 2 (Your suggestion) !! ---
            // Set status to 'verified' instead of 'completed'
            $appointment->status = 'verified'; 
            // --- !! END BUG FIX 2 !! ---

            // --- !! BUG FIX 1 (500 Error) !! ---
            // The 'session_proof' column does not exist, so we will not try to save to it.
            // $appointment->session_proof = "Auto-verified by MeetReport ID: " . $report->id;
            // --- !! END BUG FIX 1 !! ---
            
            $appointment->save();

            Log::info('Appointment ' . $appointment->id . ' auto-verified by MeetReport.');
            
            return response()->json([
                'success' => true,
                'message' => 'Sync successful! Session has been auto-verified.'
            ]);

        } else {
            // Teacher was NOT found. Mark for admin review.
            $appointment->status = 'conflict';
            
            // --- !! BUG FIX 1 (500 Error) !! ---
            // The 'session_proof' column does not exist, so we will not try to save to it.
            // $appointment->session_proof = "Report synced. Teacher not found in log. Admin review needed.";
            // --- !! END BUG FIX 1 !! ---
            
            $appointment->save();

            Log::warning('Appointment ' . $appointment->id . ' synced but teacher name not found. Marked as conflict.');

            return response()->json([
                'success' => true,
                'message' => 'Sync successful! Marked for Admin review (Teacher not found in log).'
            ]);
        }
    }
}