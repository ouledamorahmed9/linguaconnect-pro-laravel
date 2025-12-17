<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Subscription; // <-- Import Subscription
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Import DB for transactions

class SessionVerificationController extends Controller
{
    /**
     * Display a listing of pending sessions for verification.
     */
    public function index()
    {
        $pendingSessions = Appointment::where('status', 'pending_verification')
            ->with('client.coordinator', 'teacher')
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        // Decrypt and parse extension_data for each session
        $pendingSessions->getCollection()->transform(function ($session) {
            $decrypted = $this->decryptExtensionData($session->extension_data);
            $session->reportData = json_decode($decrypted, true);
            $session->rawExtensionData = $session->extension_data;
            return $session;
        });
        
        return view('admin.sessions.index', [
            'sessions' => $pendingSessions,
        ]);
    }

    /**
     * Verify a completed session.
     */
    public function verify(Appointment $appointment)
    {
        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'This session has already been processed.']);
        }

        try {
            DB::transaction(function () use ($appointment) {
                if ($appointment->completion_status === 'completed') {
                    $subscription = Subscription::where('user_id', $appointment->client_id)
                        ->where('status', 'active')
                        ->where('ends_at', '>', now())
                        ->whereColumn('lessons_used', '<', 'total_lessons')
                        ->first();

                    if ($subscription) {
                        $subscription->increment('lessons_used');
                        $subscription->refresh();

                        if ($subscription->lessons_used >= $subscription->total_lessons) {
                            $subscription->status = 'expired';
                            $subscription->save();
                        }
                    }
                }

                $appointment->status = 'verified';
                $appointment->save();
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'An error occurred during verification. ' . $e->getMessage()]);
        }

        return redirect()->route('admin.sessions.verify.index')->with('status', 'تمت مراجعة الحصة بنجاح.');
    }

    /**
     * إلغاء الحصة بشكل نهائي (Hard Reject)
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        if ($appointment->status !== 'pending_verification') {
            return redirect()->back()->withErrors(['message' => 'تمت معالجة هذه الحصة مسبقاً.']);
        }

        try {
            DB::transaction(function () use ($appointment) {
                $appointment->status = 'cancelled';
                $appointment->save();
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'حدث خطأ. ' . $e->getMessage()]);
        }

        return redirect()->route('admin.sessions.verify.index')->with('status', 'تم إلغاء الحصة بنجاح.');
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