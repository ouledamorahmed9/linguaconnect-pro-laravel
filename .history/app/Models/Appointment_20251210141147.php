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

    /**
     * Get the client (User) that this appointment belongs to.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the teacher (User) that this appointment belongs to.
     */
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
     * --- NEW FEATURE: Decrypt Extension Data ---
     * Access via: $appointment->decrypted_meet_report
     */
    public function getDecryptedMeetReportAttribute()
    {
        $payload = $this->extension_data;

        if (empty($payload)) {
            return null;
        }

        // 1. Check if it's JSON
        $json = json_decode($payload, true);
        if (!is_array($json)) {
            return null; 
        }

        // 2. Check if it's the Encrypted Format (has encKey, ciphertext, etc)
        if (!isset($json['encKey'], $json['iv'], $json['ciphertext'], $json['tag'])) {
            // It's likely already decrypted or plain JSON, return it as-is
            return $json;
        }

        // 3. Decrypt Logic
        try {
            $privKeyPem = env('MEETLIST_PRIV_KEY');
            if (!$privKeyPem) {
                return ['error' => 'Server Private Key (MEETLIST_PRIV_KEY) not found in .env'];
            }

            $privKey = openssl_pkey_get_private($privKeyPem);
            if (!$privKey) {
                return ['error' => 'Invalid Private Key format'];
            }

            // Decode Base64 parts
            $encKey = base64_decode($json['encKey']);
            $iv = base64_decode($json['iv']);
            $ciphertext = base64_decode($json['ciphertext']);
            $tag = base64_decode($json['tag']);

            // Decrypt the AES Key using RSA Private Key
            $aesKey = null;
            if (!openssl_private_decrypt($encKey, $aesKey, $privKey, OPENSSL_PKCS1_OAEP_PADDING)) {
                return ['error' => 'Failed to decrypt AES key.'];
            }

            // Decrypt the Data using AES-256-GCM
            $plaintext = openssl_decrypt(
                $ciphertext,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            if ($plaintext === false) {
                return ['error' => 'Decryption integrity check failed.'];
            }

            return json_decode($plaintext, true);

        } catch (\Exception $e) {
            return ['error' => 'Decryption error: ' . $e->getMessage()];
        }
    }
}