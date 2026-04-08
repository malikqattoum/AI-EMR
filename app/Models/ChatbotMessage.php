<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'direction',
        'content',
        'message_type',
        'payload',
        'status',
        'platform_message_id',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Get the conversation that owns this message.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatbotConversation::class);
    }

    /**
     * Scope for inbound messages.
     */
    public function scopeInbound($query)
    {
        return $query->where('direction', 'inbound');
    }

    /**
     * Scope for outbound messages.
     */
    public function scopeOutbound($query)
    {
        return $query->where('direction', 'outbound');
    }

    /**
     * Scope for failed messages.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Mark message as delivered.
     */
    public function markAsDelivered(): bool
    {
        return $this->update(['status' => 'delivered']);
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(): bool
    {
        return $this->update(['status' => 'read']);
    }

    /**
     * Mark message as failed.
     */
    public function markAsFailed(string $error): bool
    {
        return $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }
}
