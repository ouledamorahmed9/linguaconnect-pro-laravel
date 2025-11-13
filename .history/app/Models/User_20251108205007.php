<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Subscription;
use App\Models\Appointment;
use Carbon\Carbon; // <-- ** ADDED THIS IMPORT **
use Laravel\Sanctum\HasApiTokens; // <-- ADD THIS LINE

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable; // <-- ADD HasApiTokens HERE

    use HasFactory, Notifiable;

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Get the subscriptions for the user (client).
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * ---
     * ** NEW PROFESSIONAL RELATIONSHIP **
     * ---
     * Get only the *active* subscriptions for the user (client).
     * An active subscription is not expired and has remaining lessons.
     */
    public function activeSubscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)
                    ->where('status', 'active')
                    ->where('ends_at', '>', Carbon::now())
                    ->whereColumn('lessons_used', '<', 'total_lessons');
    }

    /**
     * ---
     * ** NEW PROFESSIONAL METHOD **
     * ---
     * Check if the user (client) has at least one active subscription
     * with remaining lesson credits.
     */
    public function hasActiveSubscription(): bool
    {
        // We use `exists()` for a highly efficient database query (much faster than count()).
        // This is the single source of truth for subscription status.
        return $this->activeSubscriptions()->exists();
    }


    /**
     * Get the appointments for this user (either as a client or teacher).
     */
    public function appointments(): HasMany
    {
        if ($this->hasRole('teacher')) {
            return $this->hasMany(Appointment::class, 'teacher_id');
        } else {
            return $this->hasMany(Appointment::class, 'client_id');
        }
    }

    /**
     * Get the clients that are assigned to this user (teacher).
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_teacher', 'user_id', 'client_id');
    }

    /**
     * Get the teachers that this user (client) is assigned to.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_teacher', 'client_id', 'user_id');
    }
}