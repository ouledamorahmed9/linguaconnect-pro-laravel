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
     * --- Decrypt Extension Data ---
     * Accessor: $appointment->decrypted_meet_report
     * Automatically handles decryption of the raw extension_data.
     */
    public function getDecryptedMeetReportAttribute()
    {
        $payload = $this->extension_data;

        if (empty($payload)) {
            return null;
        }

        // 1. Try to decode the JSON
        $json = json_decode($payload, true);
        
        // If it's not valid JSON, return null
        if (!is_array($json)) {
            return null; 
        }

        // 2. If it is JSON but not encrypted (legacy), return it as-is
        if (!isset($json['encKey'], $json['iv'], $json['ciphertext'], $json['tag'])) {
            return $json; 
        }

        // 3. Get Private Key & Auto-Fix Formatting
        $privKeyPem = env('MEETLIST_PRIV_KEY');
        if (!$privKeyPem) {
            return ['error' => 'Server missing Private Key'];
        }

        // Fix: Replace literal "\n" with real newlines if the key is single-lined
        if (strpos($privKeyPem, '\n') !== false) {
            $privKeyPem = str_replace('\n', "\n", $privKeyPem);
        }

        $privKey = openssl_pkey_get_private($privKeyPem);
        if (!$privKey) {
            return ['error' => 'Invalid Private Key format'];
        }

        try {
            // 4. Decode Base64 parts
            $encKey = base64_decode($json['encKey']);
            $iv = base64_decode($json['iv']);
            $ciphertext = base64_decode($json['ciphertext']);
            $tag = base64_decode($json['tag']);

            // 5. Decrypt the AES Key using RSA
            $aesKey = null;
            if (!openssl_private_decrypt($encKey, $aesKey, $privKey, OPENSSL_PKCS1_OAEP_PADDING)) {
                return ['error' => 'Failed to decrypt session key'];
            }

            // 6. Decrypt the Data using AES-256-GCM
            $plaintext = openssl_decrypt(
                $ciphertext,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            if ($plaintext === false) {
                return ['error' => 'Decryption integrity failed'];
            }

            return json_decode($plaintext, true);

        } catch (\Exception $e) {
            return ['error' => 'Decryption error: ' . $e->getMessage()];
        }
    }
}