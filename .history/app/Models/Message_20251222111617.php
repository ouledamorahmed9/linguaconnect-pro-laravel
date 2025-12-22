<? php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'body',
        'category',
        'recipient_type',
        'is_read',
        'read_at',
    ];

    /**
     * The attributes that should be cast. 
     */
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User:: class, 'sender_id');
    }

    /**
     * Get the recipient of the message. 
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Scope for unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for messages by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Mark the message as read. 
     */
    public function markAsRead(): void
    {
        if (! $this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Get the category label in Arabic.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'urgent' => 'عاجل',
            'announcement' => 'إعلان',
            'notice' => 'تنبيه',
            default => 'عام',
        };
    }

    /**
     * Get the category color for UI.
     */
    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'urgent' => 'red',
            'announcement' => 'blue',
            'notice' => 'yellow',
            default => 'gray',
        };
    }
}