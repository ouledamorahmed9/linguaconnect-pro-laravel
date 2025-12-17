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
        'extension_data', // Stores the raw encrypted string
        'teacher_notes',
        'session_proof_id',
        'completion_status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // --- Relationships ---

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
     * --- PROFESSIONAL DECRYPTION ACCESSOR ---
     * Access this property via: $appointment->decrypted_meet_report
     * This keeps the logic centralized and secure.
     */
    public function getDecryptedMeetReportAttribute()
    {
        $payload = $this->extension_data;

        if (empty($payload)) {
            return null;
        }

        // 1. Decode JSON envelope
        $json = json_decode($payload, true);
        if (!is_array($json)) {
            return null; // Not valid JSON
        }

        // 2. If it's simple plaintext (legacy), return it
        if (!isset($json['encKey'], $json['iv'], $json['ciphertext'], $json['tag'])) {
            return $json;
        }

        // 3. Prepare Private Key (Fixing formatting issues automatically)
        $privKeyPem = env('MEETLIST_PRIV_KEY');
        if (!$privKeyPem) {
            return ['error' => 'Server Configuration Error: Missing Private Key'];
        }

        // Fix: Replace literal "\n" characters with actual newlines if necessary
        if (strpos($privKeyPem, '\n') !== false) {
            $privKeyPem = str_replace('\n', "\n", $privKeyPem);
        }
        
        $privKey = openssl_pkey_get_private($privKeyPem);
        if (!$privKey) {
            return ['error' => 'Server Configuration Error: Invalid Private Key Format'];
        }

        // 4. Decrypt Process
        try {
            $encKey = base64_decode($json['encKey']);
            $iv = base64_decode($json['iv']);
            $ciphertext = base64_decode($json['ciphertext']);
            $tag = base64_decode($json['tag']);

            // A. Decrypt the AES Key using RSA
            $aesKey = null;
            if (!openssl_private_decrypt($encKey, $aesKey, $privKey, OPENSSL_PKCS1_OAEP_PADDING)) {
                return ['error' => 'Security Check Failed: Could not decrypt session key.'];
            }

            // B. Decrypt the Data using AES-256-GCM
            $plaintext = openssl_decrypt($ciphertext, 'aes-256-gcm', $aesKey, OPENSSL_RAW_DATA, $iv, $tag);

            if ($plaintext === false) {
                return ['error' => 'Integrity Check Failed: Data may have been tampered with.'];
            }

            return json_decode($plaintext, true);

        } catch (\Exception $e) {
            return ['error' => 'Decryption Error: ' . $e->getMessage()];
        }
    }
}