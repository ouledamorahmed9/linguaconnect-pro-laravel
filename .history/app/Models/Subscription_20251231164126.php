<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type', // e.g., 'monthly', 'yearly'
        'start_date',
        'end_date',
        'status', // e.g., 'active', 'inactive', 'cancelled'
        'payment_status',
        'lesson_credits', // Add this line
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->end_date->isFuture();
    }
}