<?php

namespace App\Services\DataMigration\Parsers\Traits;

trait ValidatesFilePath
{
    /**
     * Validate and resolve the real path of a file
     *
     * @throws \RuntimeException if file does not exist
     */
    protected function validatePath(string $filePath): string
    {
        $realPath = realpath($filePath);
        if ($realPath === false) {
            throw new \RuntimeException("File not found: {$filePath}");
        }
        return $realPath;
    }
}
