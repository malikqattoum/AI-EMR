<?php

namespace App\Services\DataMigration;

class FieldMapping
{
    public function __construct(
        public string $sourceColumn,
        public string $targetField,
        public float $confidence, // 0.0 - 1.0
        public bool $confirmed = false
    ) {}
}
