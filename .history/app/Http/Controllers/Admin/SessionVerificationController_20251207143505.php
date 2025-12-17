<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Subscription; // <-- ** Import Subscription **
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- ** Import DB for transactions **

class SessionVerificationController extends Controller
{
    /**
     * Display a listing of pending sessions for verification.
     */
    public function index()
    {
        // --- THIS IS THE FIX ---
        // 1. We only get sessions that are 'pending_verification'.
        // 2. We load the client and teacher data for the table.
        $pendingSessions = Appointment::where('status', 'pending_verification')
                                      ->with('client.coordinator', 'teacher') // ** هذا هو التعديل **
                                      ->orderBy('start_time', 'desc')
                                      ->paginate(15);
        
        // 3. We pass the data to the view.
        return view('admin.sessions.index', [
            'sessions' => $pendingSessions,
        ]);
        // --- END OF FIX ---
    }

    /**
     * Verify a completed session.
     * This is the core of your new business logic.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Appointment $appointment)
    {
        // Ensure we are only processing pending sessions
        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'This session has already been processed.']);
        }

        try {
            // Use a database transaction for safety.
            DB::transaction(function () use ($appointment) {
                
                // ** PROFESSIONAL LOGIC **
                // Only decrement a lesson credit if the session was
                // successfully 'completed' by the teacher.
                if ($appointment->completion_status === 'completed') {
                    
                    // Find the client's active subscription
                    $subscription = Subscription::where('user_id', $appointment->client_id)
                        ->where('status', 'active')
                        ->where('ends_at', '>', now())
                        ->whereColumn('lessons_used', '<', 'total_lessons')
                        ->first();
                    
                    // If they have one, increment its 'lessons_used' count
                    if ($subscription) {
                        $subscription->increment('lessons_used');

                        // --- ** THIS IS THE NEW LOGIC (PRIORITY 5) ** ---
                        // After incrementing, check if all lessons have been used.
                        // We refresh the model to get the new 'lessons_used' value.
                        $subscription->refresh();
                        
                        if ($subscription->lessons_used >= $subscription->total_lessons) {
                            $subscription->status = 'expired';
                            $subscription->save(); // Save the new 'expired' status
                        }
                        // --- ** END OF NEW LOGIC ** ---
                    }
                }
                
                // ** ALWAYS **: Mark the appointment as 'verified'
                // This confirms the admin has seen the report,
                // regardless of the outcome.
                $appointment->status = 'verified';
                $appointment->save();

            });

        } catch (\Exception $e) {
            // If anything went wrong, send an error
            return redirect()->back()->withErrors(['message' => 'An error occurred during verification. ' . $e->getMessage()]);
        }

        // If successful, send a success message
        return redirect()->route('admin.sessions.verify.index')->with('status', 'تمت مراجعة الحصة بنجاح.');
    }
    /**
     * إلغاء الحصة بشكل نهائي
     * (Hard Reject)
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        // 1. التأكد أن الحصة بانتظار المراجعة
        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'تمت معالجة هذه الحصة مسبقاً.']);
        }

        try {
            DB::transaction(function () use ($appointment) {
                // 2. تغيير الحالة إلى "ملغاة" مباشرة
                $appointment->status = 'cancelled';
                $appointment->save();
                // (لا يتم إنشاء أي نزاع)
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'حدث خطأ. ' . $e->getMessage()]);
        }

        // 3. إرجاع رسالة نجاح
        return redirect()->route('admin.sessions.verify.index')->with('status', 'تم إلغاء الحصة بنجاح.');
    }
    // --- ** انتهت الإضافة ** ---

    // In app/Http/Controllers/Admin/SessionsController.php (or equivalent)
public function index()
{
}

/**
 * Decrypts the encrypted payload from the extension.
 * Expects JSON with {version, alg, encKey, iv, ciphertext, tag}.
 * Falls back to original payload if decryption fails or if not encrypted.
 */
private function decryptExtensionData(?string $payload): ?string
{
    if (empty($payload)) return null;

    $json = json_decode($payload, true);
    if (!is_array($json) || !isset($json['encKey'], $json['iv'], $json['ciphertext'], $json['tag'])) {
        return $payload; // not encrypted or unexpected
    }

    $privKeyPem = env('MEETLIST_PRIV_KEY');
    if (!$privKeyPem) return $payload;

    $privKey = openssl_pkey_get_private($privKeyPem);
    if (!$privKey) return $payload;

    $encKey = base64_decode($json['encKey']);
    $iv = base64_decode($json['iv']);
    $ciphertext = base64_decode($json['ciphertext']);
    $tag = base64_decode($json['tag']);

    $aesKey = null;
    if (!openssl_private_decrypt($encKey, $aesKey, $privKey, OPENSSL_PKCS1_OAEP_PADDING)) {
        return $payload;
    }

    $plaintext = openssl_decrypt(
        $ciphertext,
        'aes-256-gcm',
        $aesKey,
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );

    return $plaintext ?: $payload;
}
}