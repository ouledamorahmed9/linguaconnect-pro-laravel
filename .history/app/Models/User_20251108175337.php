<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Subscription;
use App\Models\Appointment;
use App\Models\WeeklySlot;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'subject',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Get the subscriptions for the client.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    /**
     * ---
     * ** THIS IS THE NEW "BRAIN" OF OUR FEATURE **
     * ---
     * Helper function to check if a client's subscription is active.
     */
    public function hasActiveSubscription(): bool
    {
        // This is only relevant for clients
        if (!$this->hasRole('client')) {
            return false;
        }

        // Use 'where' clause for a simple existence check, which is faster.
        return $this->subscriptions()
                    ->where('status', 'active')
                    ->where('ends_at', '>', now())
                    ->whereColumn('lessons_used', '<', 'total_lessons')
                    ->exists();
    }
    
    /**
     * Get the teachers for the client.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_teacher', 'client_id', 'teacher_id');
    }

    /**
     * Get the clients for the teacher.
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_teacher', 'teacher_id', 'client_id');
    }

    /**
     * Get the weekly slots for the teacher.
     */
    public function weeklySlotsAsTeacher(): HasMany
    {
        return $this->hasMany(WeeklySlot::class, 'teacher_id');
    }

    /**
     * Get the weekly slots for the client.
     */
    public function weeklySlotsAsClient(): HasMany
    {
        return $this->hasMany(WeeklySlot::class, 'client_id');
    }

    /**
     * Get the appointments (logs) for the client.
     */
    public function appointmentsAsClient(): HasMany
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }
    
    /**
     * Get the appointments (logs) for the teacher.
     */
    public function appointmentsAsTeacher(): HasMany
    {
        return $this->hasMany(Appointment::class, 'teacher_id');
    }
}