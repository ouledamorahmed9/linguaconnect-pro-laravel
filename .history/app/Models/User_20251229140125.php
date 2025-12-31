<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Subscription;
use App\Models\Appointment;
use App\Models\WeeklySlot;
use App\Models\StudySubject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Message;


class User extends Authenticatable
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
        'subject',
        'profile_photo_path',
        'created_by_user_id',
        'phone',
        'study_subject_id',
        'referral_code', // <--- Add this
        'referrer_id',   // <--- Add this
        'bio', 'banner_photo_path'// <--- ADD THESE TWO
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
     * Get only the active subscriptions for the user (client).
     */
public function activeSubscription()
{
    return $this->hasOne(Subscription::class)
                ->where('status', 'active')
                ->where('ends_at', '>', now())
                ->latest();
}
    /**
     * Check if the user (client) has at least one active subscription with remaining lessons.
     */
    public function hasActiveSubscription(): bool
    {
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
        return $this->belongsToMany(User:: class, 'client_teacher', 'user_id', 'client_id');
    }

    /**
     * Get the teachers that this user (client) is assigned to.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_teacher', 'client_id', 'user_id');
    }

    /**
     * Get weekly slots this user (as student) is part of (multi-student sessions).
     */
    public function weeklySlots(): BelongsToMany
    {
        return $this->belongsToMany(WeeklySlot::class, 'weekly_slot_student', 'student_id', 'weekly_slot_id');
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

    /**
     * The coordinator who created this client.
     */
    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * All clients managed by this coordinator.
     */
    public function managedClients(): HasMany
    {
        return $this->hasMany(User::class, 'created_by_user_id');
    }

    /**
     * Get the study subject that the user wants to learn.
     */
    public function studySubject(): BelongsTo
    {
        return $this->belongsTo(StudySubject::class);
    }

        /**
     * Get messages sent by this user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message:: class, 'recipient_id');
    }

    /**
     * Get unread messages count for this user.
     */
    public function getUnreadMessagesCountAttribute(): int
    {
        return $this->receivedMessages()->unread()->count();
    }

    // Referral System Logic
    // ==========================================

    /**
     * The person who invited this user.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * The list of people this user has invited.
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    /**
     * Boot function to auto-generate referral code on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Only generate if not already set
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateUniqueReferralCode($user->name);
            }
        });
    }

    /**
     * Helper to generate a unique readable code (e.g., "AHMED-X9Y2")
     */
    private static function generateUniqueReferralCode($name)
    {
        // Take first 3 letters of name (or 'USER' if empty), uppercase
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $name) ?: 'USER', 0, 3));
        
        do {
            // Add a random 5-character string
            $code = $prefix . '-' . strtoupper(\Illuminate\Support\Str::random(5));
        } while (self::where('referral_code', $code)->exists()); // Ensure uniqueness

        return $code;
    }

    /**
     * Get the reviews this user (teacher) has received.
     */
    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'teacher_id');
    }

    /**
     * Get the average rating.
     */
    public function getAverageRatingAttribute()
    {
        return round($this->receivedReviews()->avg('rating') ?? 5, 1);
    }

    /**
     * Get Reviews Count
     */
    public function getReviewsCountAttribute()
    {
        return $this->receivedReviews()->count();
    }

    /**
     * Get Banner URL
     */
    public function getBannerUrlAttribute()
    {
        return $this->banner_photo_path 
            ? Storage::url($this->banner_photo_path) 
            : 'https://images.unsplash.com/photo-1557683316-973673baf926?q=80&w=2029&auto=format&fit=crop'; // Default Banner
    }
}
