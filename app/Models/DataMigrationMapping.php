<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataMigrationMapping extends Model
{
    use SoftDeletes;

    protected $table = 'data_migration_mappings';

    protected $fillable = [
        'source_system',
        'entity_type',
        'source_column',
        'target_field',
        'confidence',
    ];

    protected $casts = [
        'confidence' => 'float',
    ];
}
