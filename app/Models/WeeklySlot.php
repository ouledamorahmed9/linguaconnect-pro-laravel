<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    
    class WeeklySlot extends Model
    {
        use HasFactory;
    
        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
            'teacher_id',
            'client_id',
            'day_of_week',
            'start_time',
            'end_time',
        ];
    
        /**
         * Get the teacher for this weekly slot.
         */
        public function teacher(): BelongsTo
        {
            return $this->belongsTo(User::class, 'teacher_id');
        }
    
        /**
         * Get the client for this weekly slot.
         */
        public function client(): BelongsTo
        {
            return $this->belongsTo(User::class, 'client_id');
        }
    }

