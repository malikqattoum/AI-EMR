<?php

namespace App\Services\DataMigration\Parsers;

use App\Services\DataMigration\NormalizedRecord;
use App\Services\DataMigration\Parsers\Contracts\ParserInterface;
use App\Services\DataMigration\Parsers\Traits\ValidatesFilePath;

class JsonParser implements ParserInterface
{
    use ValidatesFilePath;

    /**
     * Parse JSON file and return normalized records
     *
     * @return NormalizedRecord[]
     */
    public function parse(string $filePath): array
    {
        $filePath = $this->validatePath($filePath);

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new \RuntimeException("Cannot read file: {$filePath}");
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON: " . json_last_error_msg());
        }

        // Handle single object with nested data
        if (is_object($data) && !is_array($data)) {
            $data = [$this->objectToArray($data)];
        }

        // Handle associative array (single record)
        if (is_array($data) && !$this->isList($data)) {
            $data = [$data];
        }

        if (!is_array($data)) {
            return [];
        }

        $records = [];
        foreach ($data as $index => $item) {
            if (!is_array($item)) {
                continue;
            }

            $flatData = $this->flatten($item);
            $records[] = new NormalizedRecord(
                data: $flatData,
                rowNumber: $index + 1
            );
        }

        return $records;
    }

    /**
     * Detect column names from the file
     *
     * @return string[]
     */
    public function detectColumns(string $filePath): array
    {
        $filePath = $this->validatePath($filePath);

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new \RuntimeException("Cannot read file: {$filePath}");
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        // Handle single object with nested data
        if (is_object($data) && !is_array($data)) {
            $data = [$this->objectToArray($data)];
        }

        // Handle associative array (single record)
        if (is_array($data) && !$this->isList($data)) {
            $data = [$data];
        }

        if (empty($data) || !is_array($data)) {
            return [];
        }

        // Get columns from first record
        $firstRecord = reset($data);
        if (!is_array($firstRecord)) {
            return [];
        }

        $flatRecord = $this->flatten($firstRecord);
        return array_keys($flatRecord);
    }

    /**
     * Check if this parser supports the given file type
     */
    public function supports(string $fileType): bool
    {
        return strtolower($fileType) === 'json';
    }

    /**
     * Flatten nested array/object to dot-notation keys
     */
    private function flatten(array $data, string $prefix = ''): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $newKey = $prefix === '' ? $key : "{$prefix}.{$key}";

            if (is_array($value) && !$this->isList($value)) {
                // Associative array - recurse
                $result = array_merge($result, $this->flatten($value, $newKey));
            } elseif (is_array($value) && $this->isList($value)) {
                // List array - store as JSON string or first element
                if (empty($value)) {
                    $result[$newKey] = '';
                } else {
                    // Store array values as JSON encoded string
                    $result[$newKey] = json_encode($value);
                }
            } elseif (is_object($value)) {
                // Object - recurse
                $result = array_merge($result, $this->flatten($this->objectToArray($value), $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    /**
     * Convert object to array recursively
     */
    private function objectToArray($obj): array
    {
        if (is_object($obj)) {
            $obj = (array)$obj;
        }
        if (is_array($obj)) {
            foreach ($obj as &$value) {
                if (is_object($value) || is_array($value)) {
                    $value = $this->objectToArray($value);
                }
            }
        }
        return $obj;
    }

    /**
     * Check if array is a list (sequential numeric keys starting at 0)
     */
    private function isList(array $arr): bool
    {
        if (empty($arr)) {
            return true;
        }
        return array_keys($arr) === range(0, count($arr) - 1);
    }
}
