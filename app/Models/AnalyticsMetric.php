<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsMetric extends Model
{
    use HasFactory;

    protected $table = 'analytics_metrics';

    protected $fillable = [
        'dashboard_id',
        'dashboard_widget_id',
        'metric_key',
        'metric_type',
        'data',
        'configuration',
        'is_active',
    ];

    protected $casts = [
        'data' => 'array',
        'configuration' => 'array',
        'is_active' => 'boolean',
    ];

    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(AnalyticsDashboard::class, 'dashboard_id');
    }

    public function widget(): BelongsTo
    {
        return $this->belongsTo(DashboardWidget::class, 'dashboard_widget_id');
    }
}
