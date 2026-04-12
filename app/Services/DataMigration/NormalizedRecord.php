<?php

namespace App\Services\DataMigration;

class NormalizedRecord
{
    public function __construct(
        public array $data,           // ['column_name' => 'value']
        public array $sourceIds = [], // ['source_system' => 'id']
        public int $rowNumber = 0,
        public bool $valid = true,
        public string $validationError = ''
    ) {}
}
