<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardFilter extends Model
{
    use HasFactory;

    protected $table = 'dashboard_filters';

    protected $fillable = [
        'dashboard_id',
        'field',
        'operator',
        'default_value',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(AnalyticsDashboard::class, 'dashboard_id');
    }
}
