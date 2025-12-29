<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'type',           // Matches the config keys: 'normal', 'vip', 'duo', 'private'
        'status',         // 'active', 'expired', 'cancelled'
        'starts_at',
        'ends_at',
        'payment_status', // <--- Ensure this is here

        'total_lessons',  // Crucial for the progress bar
        'lessons_used',   // Crucial for the progress bar
        'price',
        'currency'
    ];

    /**
     * The attributes that should be cast to native types.
     * This ensures $subscription->ends_at->diffInDays() works in the view.
     */
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'total_lessons' => 'integer',
        'lessons_used' => 'integer',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Helper to check if active
     */
    public function isActive()
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }
}