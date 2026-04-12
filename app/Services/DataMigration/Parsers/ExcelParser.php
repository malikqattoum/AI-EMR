<?php

namespace App\Services\DataMigration\Parsers;

use App\Services\DataMigration\NormalizedRecord;
use App\Services\DataMigration\Parsers\Contracts\ParserInterface;
use App\Services\DataMigration\Parsers\Traits\ValidatesFilePath;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelParser implements ParserInterface
{
    use ValidatesFilePath;

    /**
     * Parse Excel file and return normalized records
     *
     * @return NormalizedRecord[]
     */
    public function parse(string $filePath): array
    {
        $filePath = $this->validatePath($filePath);

        $inputFileType = $this->getFileType($filePath);
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        // Get headers from first row
        $headers = [];
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $cellValue = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
            $headers[$col] = $cellValue !== null ? (string)$cellValue : '';
        }

        $records = [];

        // Start from row 2 (skip headers)
        for ($row = 2; $row <= $highestRow; $row++) {
            $data = [];
            $isValid = true;
            $validationError = '';

            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $columnName = $headers[$col] !== '' ? $headers[$col] : "column_{$col}";

                // Check if this row has any data
                if ($cellValue !== null && $cellValue !== '') {
                    $data[$columnName] = $cellValue;
                }
            }

            // Check if row has meaningful data
            if (empty($data)) {
                $isValid = false;
                $validationError = "Row is empty";
            }

            $records[] = new NormalizedRecord(
                data: $data,
                rowNumber: $row - 1 // Normalize row number to start from 1 (after header)
            );
        }

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

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

        $inputFileType = $this->getFileType($filePath);
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($filePath);
        try {
            $worksheet = $spreadsheet->getActiveSheet();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            $headers = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                $headers[] = $cellValue !== null ? (string)$cellValue : '';
            }

            return $headers;
        } finally {
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }
    }

    /**
     * Check if this parser supports the given file type
     */
    public function supports(string $fileType): bool
    {
        return in_array(strtolower($fileType), ['xls', 'xlsx']);
    }

    /**
     * Get the file type based on extension
     */
    private function getFileType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'xlsx':
                return 'Xlsx';
            case 'xls':
                return 'Xls';
            case 'csv':
                return 'Csv';
            default:
                throw new \InvalidArgumentException("Unsupported file type: {$extension}");
        }
    }
}
