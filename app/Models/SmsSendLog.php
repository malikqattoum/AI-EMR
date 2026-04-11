<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsSendLog extends Model
{
    use HasFactory;

    protected $table = 'sms_send_logs';

    protected $fillable = [
        'user_id',
        'provider',
        'recipient',
        'message',
        'status',
        'provider_message_id',
        'error_message',
        'cost',
        'segments',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'cost' => 'decimal:4',
        'segments' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for failed messages
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for successful messages
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for a specific provider
     */
    public function scopeProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    /**
     * Scope for a specific date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sent_at', [$startDate, $endDate]);
    }
}
