<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Subscription extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan_type',
        'starts_at',
        'ends_at',
        'status',
        'total_lessons',
        'lessons_used',
    ];

    /**
     * THIS IS THE FIX:
     * The attributes that should be cast to native types.
     * This tells Laravel to treat these columns as 'datetime' objects.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
    
    /**
     * Get the user (client) that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the appointments that belong to this subscription.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Configure the activity log options for this model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['plan_type', 'status', 'ends_at', 'total_lessons'])
            ->setDescriptionForEvent(function(string $eventName) {
                $clientName = $this->user->name ?? 'Unknown Client';
                return "Subscription for client '{$clientName}' was {$eventName}";
            })
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}