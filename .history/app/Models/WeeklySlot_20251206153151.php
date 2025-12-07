<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WeeklySlot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'teacher_id',
        'client_id', // kept for backward compatibility
        'day_of_week',
        'start_time',
        'end_time',
    ];

    /**
     * Get the teacher for this weekly slot.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the (legacy) single client for this weekly slot.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the students linked to this slot (multi-student support).
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'weekly_slot_student', 'weekly_slot_id', 'student_id');
    }
}