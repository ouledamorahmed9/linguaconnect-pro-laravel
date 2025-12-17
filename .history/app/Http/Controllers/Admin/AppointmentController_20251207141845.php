<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function show($id)
    {
        $appointment = Appointment::with(['teacher', 'client', 'weeklySlot'])->findOrFail($id);

        // Decrypt and decode extension_data (if present)
        $decrypted = $this->decryptExtensionData($appointment->extension_data);
        $reportData = json_decode($decrypted, true);

        return view('admin.appointments.show', [
            'appointment' => $appointment,
            'reportData'  => $reportData,
            'rawExtensionData' => $appointment->extension_data, // fallback display if needed
        ]);
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
            return $payload; // not encrypted or unexpected format
        }

        $privKeyPem = env('MEETLIST_PRIV_KEY');
        if (!$privKeyPem) {
            return $payload; // no key configured
        }

        $privKey = openssl_pkey_get_private($privKeyPem);
        if (!$privKey) {
            return $payload;
        }

        $encKey = base64_decode($json['encKey']);
        $iv = base64_decode($json['iv']);
        $ciphertext = base64_decode($json['ciphertext']);
        $tag = base64_decode($json['tag']);

        // RSA-OAEP decrypt AES key
        $aesKey = null;
        if (!openssl_private_decrypt($encKey, $aesKey, $privKey, OPENSSL_PKCS1_OAEP_PADDING)) {
            return $payload;
        }

        // AES-256-GCM decrypt
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