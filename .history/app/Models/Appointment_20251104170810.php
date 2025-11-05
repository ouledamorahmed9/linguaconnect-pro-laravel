<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'teacher_id',
        'subject',
        'topic',
        'start_time',
        'end_time',
        'status',
        'teacher_notes', // <-- This was the first fix
        // 'session_proof', // <-- This was the bug
        'session_proof_id', // <-- ** THIS IS THE NEW FIX **
        'google_meet_link',
        'completion_status', // <-- ** THIS IS THE MODIFICATION **
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
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

    /**
     * Get the dispute associated with the appointment.
     */
    public function dispute(): BelongsTo
    {
        return $this->belongsTo(Dispute::class);
    }
}

