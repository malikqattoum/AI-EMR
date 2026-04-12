<?php

namespace App\Services\DataMigration;

class ImportResult
{
    public int $imported = 0;
    public int $skipped = 0;
    public int $failed = 0;
    public array $failures = []; // ['row' => 5, 'reason' => '...', 'data' => [...]]

    public function addFailure(int $row, string $reason, array $data = []): void
    {
        $this->failures[] = [
            'row' => $row,
            'reason' => $reason,
            'data' => $data,
        ];
        $this->failed++;
    }

    public function incrementImported(): void
    {
        $this->imported++;
    }

    public function incrementSkipped(): void
    {
        $this->skipped++;
    }

    public function getTotalProcessed(): int
    {
        return $this->imported + $this->skipped + $this->failed;
    }

    public function hasFailures(): bool
    {
        return $this->failed > 0;
    }

    public function toArray(): array
    {
        return [
            'imported' => $this->imported,
            'skipped' => $this->skipped,
            'failed' => $this->failed,
            'failures' => $this->failures,
        ];
    }
}
