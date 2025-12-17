<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Js;

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
        'extension_data', // We store the encrypted string here
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
     * --- SMART DECRYPTION ACCESSOR ---
     * Access via: $appointment->decrypted_meet_report
     * Automatically formats the .env key to fix "Invalid Private Key format" errors.
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

        // 2. Return if not encrypted (legacy data)
        if (!isset($json['encKey'], $json['iv'], $json['ciphertext'], $json['tag'])) {
            return $json; 
        }

        // 3. Get Key from .env
        $rawKey = env('MEETLIST_PRIV_KEY');
        if (!$rawKey) {
            return ['error' => 'Error: MEETLIST_PRIV_KEY is missing in .env file'];
        }

        // --- ** FIX: FORCE FORMAT THE KEY ** ---
        // 1. Remove existing headers if they exist (to start clean)
        $cleanKey = str_replace(
            ['-----BEGIN PRIVATE KEY-----', '-----END PRIVATE KEY-----', "\n", "\r", " "], 
            '', 
            $rawKey
        );

        // 2. Chunk it into 64 characters per line (Standard PEM format)
        $formattedKey = "-----BEGIN PRIVATE KEY-----\n" . 
                        chunk_split($cleanKey, 64, "\n") . 
                        "-----END PRIVATE KEY-----";
        // ---------------------------------------

        $privKey = openssl_pkey_get_private($formattedKey);
        if (!$privKey) {
            // Debug info: OpenSSL error
            $msg = '';
            while ($err = openssl_error_string()) { $msg .= $err . '; '; }
            return ['error' => 'Invalid Private Key format. OpenSSL: ' . $msg];
        }

        try {
            // 4. Decode Payload
            $encKey = base64_decode($json['encKey']);
            $iv = base64_decode($json['iv']);
            $ciphertext = base64_decode($json['ciphertext']);
            $tag = base64_decode($json['tag']);

            // 5. Decrypt AES Key using RSA
            $aesKey = null;
            if (!openssl_private_decrypt($encKey, $aesKey, $privKey, OPENSSL_PKCS1_OAEP_PADDING)) {
                return ['error' => 'Security: Failed to decrypt session key. Public/Private keys may not match.'];
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
                return ['error' => 'Integrity: Data decryption failed.'];
            }

            return json_decode($plaintext, true);

        } catch (\Exception $e) {
            return ['error' => 'Exception: ' . $e->getMessage()];
        }
    }
}