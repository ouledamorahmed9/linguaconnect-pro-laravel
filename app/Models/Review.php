<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'client_id', 'rating', 'comment'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}