<?php

namespace App\Models;// Based on your file, the namespace is App

use Illuminate\Contracts\Auth\MustVerifyEmail; // This was in your file, let's re-enable it
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Import the BelongsToMany relationship
use App\Models\Subscription; // Ensure Subscription model is imported
use App\Models\Appointment; // Ensure Appointment model is imported

// We will implement MustVerifyEmail as it's a professional standard
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable; // HasApiTokens is often for APIs, we'll stick to web auth

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Add 'role' to allow it to be set during creation
        'subject', // <-- ** ADD THIS LINE **
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
            'password' => 'hashed', // Add this for professional security
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

    // === NEW PROFESSIONAL RELATIONSHIPS ===

    /**
     * Get the clients that are assigned to this user (teacher).
     */
    public function clients(): BelongsToMany
    {
        // 'client_teacher' is the pivot table name
        // 'user_id' is the foreign key for the User model (the Teacher)
        // 'client_id' is the foreign key for the related model (the Client)
        return $this->belongsToMany(User::class, 'client_teacher', 'user_id', 'client_id');
    }

    /**
     * Get the teachers that this user (client) is assigned to.
     */
    public function teachers(): BelongsToMany
    {
        // Here, the keys are swapped to define the inverse relationship
        return $this->belongsToMany(User::class, 'client_teacher', 'client_id', 'user_id');
    }
}

