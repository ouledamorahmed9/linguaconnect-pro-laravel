<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class Dispute extends Model
    {
        use HasFactory;

        /**
         * The attributes that are mass assignable.
         */
        protected $fillable = [
            'appointment_id',
            'admin_id',
            'reason',
            'status',
        ];

        /**
         * Get the appointment that this dispute is for.
         */
        public function appointment(): BelongsTo
        {
            return $this->belongsTo(Appointment::class);
        }

        /**
         * Get the admin who created this dispute.
         */
        public function admin(): BelongsTo
        {
            return $this->belongsTo(User::class, 'admin_id');
        }
    }