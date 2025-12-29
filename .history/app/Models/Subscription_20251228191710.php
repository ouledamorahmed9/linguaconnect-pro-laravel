<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_type',      // <--- THE FIX: Matches your original DB column
        'type',           // Kept for compatibility with your recent migration
        'price',
        'currency',
        'payment_status',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Helper to get the full plan details from config
     */
    public function getPlanDetailsAttribute()
    {
        $plans = config('plans');
        // FIX: Use 'plan_type' instead of 'name'
        return $plans[$this->plan_type] ?? null;
    }
}