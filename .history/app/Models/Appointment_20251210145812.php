<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'teacher_id',
        'student_id',
        'subject',
        'topic',
        'start_time',
        'end_time',
        'status',
        'google_meet_link',
        'extension_data', 
        'teacher_notes',
        'session_proof_id',
        'completion_status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(Dispute::class);
    }

    public function meetReport()
    {
        return $this->hasOne(MeetReport::class);
    }

    /**
     * --- SMART REPORT ACCESSOR ---
     * Decrypts the extension data on the fly.
     * Access via: $appointment->decrypted_meet_report
     */
    public function getDecryptedMeetReportAttribute()
    {
        $payload = $this->extension_data;

        if (empty($payload)) {
            return null;
        }

        // 1. Decode JSON
        $json = json_decode($payload, true);
        if (!is_array($json)) {
            return null; 
        }

        // 2. If it's already decrypted or plain text, return it
        if (!isset($json['encKey'])) {
            return $json; 
        }

        // 3. Robust Key Formatting for your .env key
        $rawKey = env('MEETLIST_PRIV_KEY');
        if (!$rawKey) {
            return ['error' => 'Configuration Error: MEETLIST_PRIV_KEY missing in .env'];
        }

        // Strip everything to get just the base64 code
        $cleanKey = str_replace(
            ['-----BEGIN PRIVATE KEY-----', '-----END PRIVATE KEY-----', "\r", "\n", " "], 
            '', 
            $rawKey
        );

        // Re-wrap it in valid PEM format (64 chars per line)
        $formattedKey = "-----BEGIN PRIVATE KEY-----\n" . 
                        chunk_split($cleanKey, 64, "\n") . 
                        "-----END PRIVATE KEY-----";

        $privKey = openssl_pkey_get_private($formattedKey);
        
        if (!$privKey) {
            // Capture the specific OpenSSL error
            $sslError = [];
            while ($msg = openssl_error_string()) $sslError[] = $msg;
            return ['error' => 'Invalid Private Key Format. ' . implode(', ', $sslError)];
        }

        try {
            // 4. Decode Payload components
            $encKey = base64_decode($json['encKey']);
            $iv = base64_decode($json['iv']);
            $ciphertext = base64_decode($json['ciphertext']);
            $tag = base64_decode($json['tag']);

            // 5. Decrypt AES Key using RSA
            $aesKey = null;
            if (!openssl_private_decrypt($encKey, $aesKey, $privKey, OPENSSL_PKCS1_OAEP_PADDING)) {
                return ['error' => 'Security Error: Key mismatch. The extension used a different Public Key.'];
            }

            // 6. Decrypt Data using AES-GCM
            $plaintext = openssl_decrypt(
                $ciphertext,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            if ($plaintext === false) {
                return ['error' => 'Data Integrity Error: The report data is corrupted or tampered with.'];
            }

            return json_decode($plaintext, true);

        } catch (\Exception $e) {
            return ['error' => 'Decryption Exception: ' . $e->getMessage()];
        }
    }
}