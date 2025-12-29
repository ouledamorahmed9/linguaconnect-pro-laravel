<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens; // Added mostly for standard setups

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
        'profile_photo_path',
        'created_by_user_id',
        'phone', // Added based on common needs, ensuring compatibility
        'study_subject_id',
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

    // --- RELATIONSHIPS ---

    /**
     * Get the subscriptions for the user (Client).
     * This is crucial for filtering active accounts.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the teachers assigned to this user (Client).
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_teacher', 'client_id', 'user_id');
    }

    /**
     * Get the clients assigned to this user (Teacher).
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_teacher', 'user_id', 'client_id');
    }

    /**
     * Relationship to get the "Creator" (Coordinator) of this user.
     */
    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Helper to check roles easily.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Get the URL for the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}