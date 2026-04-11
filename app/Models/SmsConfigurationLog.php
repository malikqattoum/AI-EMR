<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsConfigurationLog extends Model
{
    use HasFactory;

    protected $table = 'sms_configuration_logs';

    protected $fillable = [
        'user_id',
        'provider',
        'action',
        'old_configuration',
        'new_configuration',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_configuration' => 'array',
        'new_configuration' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for a specific provider
     */
    public function scopeProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    /**
     * Scope for a specific action type
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }
}
