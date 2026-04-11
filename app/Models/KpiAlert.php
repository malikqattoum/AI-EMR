<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiAlert extends Model
{
    use HasFactory;

    protected $table = 'kpi_alerts';

    protected $fillable = [
        'kpi_threshold_id',
        'kpi_name',
        'current_value',
        'threshold_value',
        'alert_level',
        'message',
        'is_resolved',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'current_value' => 'decimal:4',
        'threshold_value' => 'decimal:4',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function threshold(): BelongsTo
    {
        return $this->belongsTo(KpiThreshold::class, 'kpi_threshold_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Scope for unresolved alerts
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope for critical alerts
     */
    public function scopeCritical($query)
    {
        return $query->where('alert_level', 'critical');
    }

    /**
     * Scope for warning alerts
     */
    public function scopeWarning($query)
    {
        return $query->where('alert_level', 'warning');
    }

    /**
     * Mark alert as resolved
     */
    public function resolve(?int $userId = null): bool
    {
        $this->is_resolved = true;
        $this->resolved_at = now();
        $this->resolved_by = $userId;

        return $this->save();
    }
}
