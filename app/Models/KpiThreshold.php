<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiThreshold extends Model
{
    use HasFactory;

    protected $table = 'kpi_thresholds';

    protected $fillable = [
        'kpi_name',
        'warning_threshold',
        'critical_threshold',
        'comparison_operator',
        'is_active',
        'notification_emails',
    ];

    protected $casts = [
        'warning_threshold' => 'decimal:4',
        'critical_threshold' => 'decimal:4',
        'is_active' => 'boolean',
        'notification_emails' => 'array',
    ];

    /**
     * Scope for active thresholds
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if a value exceeds the critical threshold
     */
    public function isCritical(float $value): bool
    {
        return match ($this->comparison_operator) {
            '>' => $value > $this->critical_threshold,
            '<' => $value < $this->critical_threshold,
            '>=' => $value >= $this->critical_threshold,
            '<=' => $value <= $this->critical_threshold,
            default => false,
        };
    }

    /**
     * Check if a value exceeds the warning threshold
     */
    public function isWarning(float $value): bool
    {
        return match ($this->comparison_operator) {
            '>' => $value > $this->warning_threshold,
            '<' => $value < $this->warning_threshold,
            '>=' => $value >= $this->warning_threshold,
            '<=' => $value <= $this->warning_threshold,
            default => false,
        };
    }
}
