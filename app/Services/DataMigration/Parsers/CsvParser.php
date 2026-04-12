<?php

namespace App\Services\DataMigration\Parsers;

use App\Services\DataMigration\NormalizedRecord;
use App\Services\DataMigration\Parsers\Contracts\ParserInterface;
use App\Services\DataMigration\Parsers\Traits\ValidatesFilePath;

class CsvParser implements ParserInterface
{
    use ValidatesFilePath;

    /**
     * Parse CSV file and return normalized records
     *
     * @return NormalizedRecord[]
     */
    public function parse(string $filePath): array
    {
        $filePath = $this->validatePath($filePath);

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Cannot open file: {$filePath}");
        }

        // Read first row to detect delimiter and get headers
        $firstRow = fgetcsv($handle, 0, ',');
        if ($firstRow === false || empty($firstRow)) {
            fclose($handle);
            return [];
        }

        // Detect delimiter
        $delimiter = $this->detectDelimiter($firstRow);

        // Rewind and re-read with detected delimiter
        rewind($handle);
        $headers = fgetcsv($handle, 0, $delimiter);
        if ($headers === false) {
            fclose($handle);
            return [];
        }

        $headers = array_map('trim', $headers);
        $records = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowNumber++;
            if (count($row) !== count($headers)) {
                $records[] = new NormalizedRecord(
                    data: [],
                    rowNumber: $rowNumber,
                    valid: false,
                    validationError: "Row has " . count($row) . " columns, expected " . count($headers)
                );
                continue;
            }

            $data = array_combine($headers, $row);
            if ($data === false) {
                $records[] = new NormalizedRecord(
                    data: [],
                    rowNumber: $rowNumber,
                    valid: false,
                    validationError: "Failed to combine headers with row data"
                );
                continue;
            }

            $records[] = new NormalizedRecord(
                data: $data,
                rowNumber: $rowNumber
            );
        }

        fclose($handle);
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

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Cannot open file: {$filePath}");
        }

        $firstRow = fgetcsv($handle, 0, ',');
        if ($firstRow === false) {
            fclose($handle);
            return [];
        }

        // Detect delimiter
        $delimiter = $this->detectDelimiter($firstRow);

        // Rewind and get headers with correct delimiter
        rewind($handle);
        $headers = fgetcsv($handle, 0, $delimiter);
        fclose($handle);

        return $headers !== false ? array_map('trim', $headers) : [];
    }

    /**
     * Check if this parser supports the given file type
     */
    public function supports(string $fileType): bool
    {
        return strtolower($fileType) === 'csv';
    }

    /**
     * Auto-detect delimiter by checking first row
     */
    private function detectDelimiter(array $row): string
    {
        $firstRowStr = implode('', $row);

        $delimiters = [
            ',' => 0,
            ';' => 0,
            "\t" => 0,
        ];

        foreach ($delimiters as $delimiter => &$count) {
            $count = substr_count($firstRowStr, $delimiter);
        }

        // Return the delimiter with highest count, default to comma
        arsort($delimiters);
        $detected = key($delimiters);

        return $detected ?: ',';
    }
}
