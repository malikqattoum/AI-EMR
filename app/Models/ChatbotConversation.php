<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatbotConversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'session_id',
        'patient_id',
        'platform',
        'platform_user_id',
        'state',
        'context',
        'metadata',
        'last_activity_at',
        'expires_at',
    ];

    protected $casts = [
        'context' => 'array',
        'metadata' => 'array',
        'last_activity_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the patient associated with the conversation.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the messages for this conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatbotMessage::class);
    }

    /**
     * Scope for active (non-expired) conversations.
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for specific platform.
     */
    public function scopePlatform($query, string $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Scope for specific platform user ID.
     */
    public function scopePlatformUserId($query, string $platformUserId)
    {
        return $query->where('platform_user_id', $platformUserId);
    }

    /**
     * Scope for specific state.
     */
    public function scopeState($query, string $state)
    {
        return $query->where('state', $state);
    }

    /**
     * Update conversation state.
     */
    public function updateState(string $state, array $context = []): self
    {
        $this->update([
            'state' => $state,
            'context' => array_merge($this->context ?? [], $context),
            'last_activity_at' => now(),
        ]);

        return $this;
    }

    /**
     * Reset conversation to idle state.
     */
    public function reset(): self
    {
        $this->update([
            'state' => 'idle',
            'context' => null,
            'last_activity_at' => now(),
        ]);

        return $this;
    }

    /**
     * Check if conversation is expired.
     */
    public function isExpired(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return true;
        }

        // Auto-expire after 30 minutes of inactivity
        if ($this->last_activity_at && $this->last_activity_at->diffInMinutes(now()) > 30) {
            return true;
        }

        return false;
    }

    /**
     * Get or create active conversation for a platform user.
     */
    public static function getOrCreateActive(string $platform, string $platformUserId, ?int $patientId = null): self
    {
        $conversation = static::platform($platform)
            ->platformUserId($platformUserId)
            ->active()
            ->first();

        if (!$conversation) {
            $conversation = static::create([
                'session_id' => str()->uuid(),
                'platform' => $platform,
                'platform_user_id' => $platformUserId,
                'patient_id' => $patientId,
                'state' => 'idle',
                'last_activity_at' => now(),
                'expires_at' => now()->addHours(24),
            ]);
        }

        return $conversation;
    }

    /**
     * Add a message to this conversation.
     */
    public function addMessage(string $direction, string $content, array $options = []): ChatbotMessage
    {
        return $this->messages()->create(array_merge([
            'direction' => $direction,
            'content' => $content,
            'message_type' => 'text',
            'status' => 'sent',
        ], $options));
    }
}
