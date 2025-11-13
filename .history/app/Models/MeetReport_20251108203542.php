<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'meeting_code',
        'meeting_url',
        'report_data',
    ];

    protected $casts = [
        'report_data' => 'array', // Automatically handle JSON
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}