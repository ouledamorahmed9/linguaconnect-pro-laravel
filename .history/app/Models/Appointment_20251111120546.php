<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

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
        'google_meet_link',
        // 'notes', // <-- This was the old, incorrect column name
        'teacher_notes', // <-- ** FIX 1: Correct column name **
        // 'session_proof', // <-- This was the old, incorrect column name
        'session_proof_id', // <-- ** FIX 2: Correct column name **
        
        'completion_status', // <-- ** FIX 3: Add new column **
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
     * Get all of the disputes for the appointment.
     * (This relationship was correct in your file list)
     */
    public function disputes(): HasMany
    {
        return $this->hasMany(Dispute::class);
    }


    public function meetReport()
{
    return $this->hasOne(MeetReport::class);
}
    
}

