<?php

namespace App\Services\DataMigration\Parsers;

use App\Services\DataMigration\NormalizedRecord;
use App\Services\DataMigration\Parsers\Contracts\ParserInterface;
use App\Services\DataMigration\Parsers\Traits\ValidatesFilePath;

class SqlDumpParser implements ParserInterface
{
    use ValidatesFilePath;

    /**
     * Parse SQL dump file and return normalized records
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

        // Find all INSERT statements
        $pattern = '/INSERT\s+INTO\s+`?(\w+)`?\s*\(([^)]+)\)\s*VALUES\s*(.+?);/is';
        $matches = [];
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        $records = [];
        $rowNumber = 0;

        foreach ($matches as $match) {
            $tableName = $match[1];
            $columnsStr = $match[2];
            $valuesStr = $match[3];

            // Parse column names
            $columns = array_map('trim', explode(',', $columnsStr));
            $columns = array_map(function ($col) {
                return trim($col, '`"[]');
            }, $columns);

            // Parse value rows - handle multi-row INSERTs
            $valuesStr = trim($valuesStr);
            $valueRows = $this->parseValueRows($valuesStr);

            foreach ($valueRows as $values) {
                $rowNumber++;
                if (count($values) !== count($columns)) {
                    $records[] = new NormalizedRecord(
                        data: [],
                        rowNumber: $rowNumber,
                        valid: false,
                        validationError: "Row has " . count($values) . " values, expected " . count($columns)
                    );
                    continue;
                }

                $data = array_combine($columns, $values);
                if ($data === false) {
                    $records[] = new NormalizedRecord(
                        data: [],
                        rowNumber: $rowNumber,
                        valid: false,
                        validationError: "Failed to combine columns with values"
                    );
                    continue;
                }

                $records[] = new NormalizedRecord(
                    data: $data,
                    rowNumber: $rowNumber
                );
            }
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

        // Find first INSERT statement
        $pattern = '/INSERT\s+INTO\s+`?(\w+)`?\s*\(([^)]+)\)\s*VALUES/is';
        if (preg_match($pattern, $content, $match)) {
            $columnsStr = $match[2];
            $columns = array_map('trim', explode(',', $columnsStr));
            return array_map(function ($col) {
                return trim($col, '`"[]');
            }, $columns);
        }

        return [];
    }

    /**
     * Check if this parser supports the given file type
     */
    public function supports(string $fileType): bool
    {
        return strtolower($fileType) === 'sql';
    }

    /**
     * Parse value rows from VALUES clause handling multi-row INSERTs
     *
     * @return array<array<string>>
     */
    private function parseValueRows(string $valuesStr): array
    {
        $rows = [];
        $currentValue = '';
        $inQuote = false;
        $quoteChar = null;
        $parenDepth = 0;
        $len = strlen($valuesStr);
        $i = 0;

        // Skip leading whitespace
        while ($i < $len && $i < strlen($valuesStr) && ctype_space($valuesStr[$i])) {
            $i++;
        }

        for (; $i < $len; $i++) {
            $char = $valuesStr[$i];

            // Handle quote state
            if ($inQuote) {
                if ($char === $quoteChar && isset($valuesStr[$i + 1]) && $valuesStr[$i + 1] === $quoteChar) {
                    // Escaped quote
                    $currentValue .= $char;
                    $i++;
                } elseif ($char === $quoteChar) {
                    // End of quote
                    $inQuote = false;
                    $currentValue .= $char;
                } else {
                    $currentValue .= $char;
                }
                continue;
            }

            // Handle quotes
            if ($char === "'" || $char === '"') {
                $inQuote = true;
                $quoteChar = $char;
                $currentValue .= $char;
                continue;
            }

            // Handle parentheses
            if ($char === '(') {
                $parenDepth++;
                $currentValue .= $char;
                continue;
            }

            if ($char === ')') {
                $parenDepth--;
                $currentValue .= $char;

                // End of a row when parenthesis depth returns to 0
                if ($parenDepth === 0) {
                    // Parse the row values
                    $rowValues = $this->parseRowValues($currentValue);
                    $rows[] = $rowValues;
                    $currentValue = '';
                }
                continue;
            }

            // Handle comma separator (only at top level)
            if ($char === ',' && $parenDepth === 0) {
                continue;
            }

            // Collect characters
            if ($parenDepth > 0 || !ctype_space($char)) {
                $currentValue .= $char;
            }
        }

        return $rows;
    }

    /**
     * Parse values from a single row string like: ('val1', 'val2', 'val3')
     *
     * @return array<string>
     */
    private function parseRowValues(string $rowStr): array
    {
        // Remove outer parentheses
        $rowStr = trim($rowStr);
        if (strpos($rowStr, '(') === 0) {
            $rowStr = substr($rowStr, 1);
        }
        if (substr($rowStr, -1) === ')') {
            $rowStr = substr($rowStr, 0, -1);
        }

        $values = [];
        $currentValue = '';
        $inQuote = false;
        $quoteChar = null;
        $len = strlen($rowStr);

        for ($i = 0; $i < $len; $i++) {
            $char = $rowStr[$i];

            if ($inQuote) {
                if ($char === $quoteChar && isset($rowStr[$i + 1]) && $rowStr[$i + 1] === $quoteChar) {
                    $currentValue .= $char;
                    $i++;
                } elseif ($char === $quoteChar) {
                    $inQuote = false;
                    $currentValue .= $char;
                } else {
                    $currentValue .= $char;
                }
                continue;
            }

            if ($char === "'" || $char === '"') {
                $inQuote = true;
                $quoteChar = $char;
                $currentValue .= $char;
                continue;
            }

            if ($char === ',') {
                $values[] = trim($currentValue);
                $currentValue = '';
                continue;
            }

            $currentValue .= $char;
        }

        // Add last value
        if ($currentValue !== '') {
            $values[] = trim($currentValue);
        }

        return $values;
    }
}
