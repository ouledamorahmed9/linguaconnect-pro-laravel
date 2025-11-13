namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\MeetReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // For debugging

class MeetReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'meetingCode' => 'required|string',
            'meetingUrl' => 'required|url',
            'meetingData' => 'required|array',
        ]);

        $user = Auth::user();
        $meetingCode = $request->input('meetingCode');
        $meetingData = $request->input('meetingData');

        // 1. Find the appointment in your database
        // We find the appointment by the Google Meet link containing the meeting code.
        $appointment = Appointment::where('teacher_id', $user->id)
            ->where('google_meet_link', 'LIKE', '%' . $meetingCode . '%')
            ->where('status', '!=', 'completed') // Only sync non-completed
            ->first();

        if (!$appointment) {
            Log::warning('Meet report sync failed: No matching appointment found for teacher ' . $user->id . ' with code ' . $meetingCode);
            return response()->json([
                'success' => false,
                'message' => 'No matching & pending appointment found in your schedule.'
            ], 404);
        }

        // 2. Save the report data
        $report = MeetReport::create([
            'appointment_id' => $appointment->id,
            'meeting_code'   => $meetingCode,
            'meeting_url'    => $request->input('meetingUrl'),
            'report_data'    => $meetingData,
        ]);

        // 3. **AUTOMATION LOGIC**
        // This is where we automate your business.
        // We can now auto-verify the session.

        // Get the service you already wrote for admins
        $verificationService = new \App\Services\SessionVerificationService();

        // Check if the teacher (host) was in the call
        $teacherName = $user->name;
        $teacherAttended = false;
        foreach ($meetingData['participants'] as $participant) {
            if (str_contains(strtolower($participant['name']), strtolower($teacherName))) {
                $teacherAttended = true;
                break;
            }
        }

        // If teacher was there, auto-verify!
        if ($teacherAttended) {
            // This calls the *exact same logic* your Admin controller uses
            $verificationService->verifySession($appointment);
            
            // We can even remove the manual proof file now
            $appointment->session_proof = "Auto-verified by MeetReport ID: " . $report->id;
            $appointment->save();

            Log::info('Appointment ' . $appointment->id . ' auto-verified by MeetReport.');
            
            return response()->json([
                'success' => true,
                'message' => 'Sync successful! Session has been auto-verified.'
            ]);
        }
        
        // If teacher was not found, mark for admin review
        $appointment->status = 'conflict'; // Or a new status like 'needs_review'
        $appointment->session_proof = "Report synced. Teacher not found in log. Admin review needed.";
        $appointment->save();

        return response()->json([
            'success' => true,
            'message' => 'Sync successful! Admin review required.'
        ]);
    }
}