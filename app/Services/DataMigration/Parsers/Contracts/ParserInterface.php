<?php

namespace App\Services\DataMigration\Parsers\Contracts;

interface ParserInterface
{
    /**
     * Parse the file and return normalized records
     * @return \App\Services\DataMigration\NormalizedRecord[]
     */
    public function parse(string $filePath): array;

    /**
     * Detect column names from the file
     * @return string[]
     */
    public function detectColumns(string $filePath): array;

    /**
     * Check if this parser supports the given file type
     */
    public function supports(string $fileType): bool;
}
