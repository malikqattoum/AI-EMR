<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataMigrationLog;
use App\Models\DataMigrationMapping;
use App\Services\DataMigration\DataImporter;
use App\Services\DataMigration\FieldMapper;
use App\Services\DataMigration\ImportResult;
use App\Services\DataMigration\NormalizedRecord;
use App\Services\DataMigration\Parsers\CsvParser;
use App\Services\DataMigration\Parsers\ExcelParser;
use App\Services\DataMigration\Parsers\JsonParser;
use App\Services\DataMigration\Parsers\SqlDumpParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DataMigrationController extends Controller
{
    protected DataImporter $importer;
    protected FieldMapper $mapper;

    public function __construct()
    {
        $this->importer = new DataImporter();
        $this->mapper = new FieldMapper();
        $this->targetFields = [
            'patient' => ['name', 'email', 'phone', 'date_of_birth', 'gender', 'age', 'address', 'city', 'state', 'zip_code', 'blood_type', 'allergies'],
            'doctor' => ['name', 'email', 'phone', 'specialty', 'license_number', 'bio', 'consultation_fee', 'languages'],
            'patient_data' => ['name', 'weight', 'height', 'blood_pressure', 'temperature', 'symptoms', 'diagnosis', 'medications', 'allergies', 'notes'],
            'diagnosis' => ['diagnosis_text', 'patient_id', 'doctor_id', 'follow_up_count'],
        ];
    }

    /**
     * Show the upload form
     */
    public function upload()
    {
        return view('admin.data-migration.index');
    }

    /**
     * Parse uploaded file and detect columns
     */
    public function parse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:51200', // 50MB max
            'entity_type' => 'required|in:doctor,patient,patient_data,diagnosis',
            'source_system' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('file');
        $entityType = $request->input('entity_type');
        $sourceSystem = $request->input('source_system', 'default');

        $extension = strtolower($file->getClientOriginalExtension());
        $parser = $this->getParser($extension);

        if (!$parser) {
            return redirect()->back()->withErrors(['file' => 'Unsupported file type. Please use CSV, Excel, JSON, or SQL.'])->withInput();
        }

        // Store file temporarily
        $tempPath = $file->store('temp/data-migration');
        $fullPath = storage_path('app/' . $tempPath);

        try {
            $columns = $parser->detectColumns($fullPath);
            $records = $parser->parse($fullPath);
            $mappings = $this->mapper->autoMap($columns, $entityType);

            Session::put('data_migration.file_path', $fullPath);
            Session::put('data_migration.file_type', $extension);
            Session::put('data_migration.entity_type', $entityType);
            Session::put('data_migration.source_system', $sourceSystem);
            Session::put('data_migration.columns', $columns);
            Session::put('data_migration.mappings', $mappings);
            Session::put('data_migration.record_count', count($records));

            return redirect()->route('admin.data-migration.review');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'Failed to parse file: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show review mappings page
     */
    public function review(Request $request)
    {
        if (!Session::has('data_migration.file_path')) {
            return redirect()->route('admin.data-migration.upload')->withErrors(['file' => 'Session expired. Please upload again.']);
        }

        $columns = Session::get('data_migration.columns', []);
        $entityType = Session::get('data_migration.entity_type');
        $mappings = Session::get('data_migration.mappings', []);
        $recordCount = Session::get('data_migration.record_count', 0);

        // Preview: re-parse just 3 records for display (not stored in session)
        $parser = $this->getParser(Session::get('data_migration.file_type'));
        $preview = [];
        if ($parser) {
            try {
                $allRecords = $parser->parse(Session::get('data_migration.file_path'));
                $preview = array_slice($allRecords, 0, 3);
            } catch (\Exception $e) {
                // Ignore preview errors
            }
        }

        return view('admin.data-migration.review', [
            'columns' => $columns,
            'mappings' => $mappings,
            'entityType' => $entityType,
            'preview' => $preview,
            'recordCount' => $recordCount,
            'targetFields' => $this->targetFields,
        ]);
    }

    /**
     * Execute the import
     */
    public function import(Request $request)
    {
        if (!Session::has('data_migration.file_path')) {
            return redirect()->route('admin.data-migration.upload')->withErrors(['file' => 'Session expired. Please upload again.']);
        }

        $filePath = Session::get('data_migration.file_path');
        $fileType = Session::get('data_migration.file_type');
        $entityType = Session::get('data_migration.entity_type');
        $sourceSystem = Session::get('data_migration.source_system');
        $hospitalId = Auth::user()->hospital_id ?? 1;

        $parser = $this->getParser($fileType);

        if (!$parser) {
            return redirect()->back()->withErrors(['file' => 'Unsupported file type']);
        }

        try {
            $records = $parser->parse($filePath);

            $result = match ($entityType) {
                'doctor' => $this->importer->importDoctors($records, $hospitalId, $sourceSystem),
                'patient' => $this->importer->importPatients($records, $hospitalId, $sourceSystem),
                'patient_data' => $this->importer->importPatientData($records, $hospitalId, $sourceSystem),
                'diagnosis' => $this->importer->importDiagnoses($records, $hospitalId, $sourceSystem),
                default => new ImportResult(),
            };

            // Log the import
            $log = DataMigrationLog::create([
                'admin_user_id' => Auth::id(),
                'source_system' => $sourceSystem,
                'file_name' => basename($filePath),
                'file_type' => $fileType,
                'entity_type' => $entityType,
                'total_rows' => count($records),
                'imported_count' => $result->imported,
                'skipped_count' => $result->skipped,
                'failed_count' => $result->failed,
                'failure_log' => $result->hasFailures() ? $result->failures : null,
                'completed_at' => now(),
            ]);

            // Clear session
            Session::forget('data_migration');

            return redirect()->route('admin.data-migration.summary', $log->id)->with('success', 'Import completed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['import' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Show import summary
     */
    public function summary(DataMigrationLog $log)
    {
        return view('admin.data-migration.summary', ['log' => $log]);
    }

    /**
     * List saved mapping templates
     */
    public function templates()
    {
        $templates = DataMigrationMapping::whereNotNull('source_system')
            ->select('source_system', 'entity_type')
            ->groupBy('source_system', 'entity_type')
            ->get();

        return view('admin.data-migration.templates', ['templates' => $templates]);
    }

    /**
     * Save a mapping template
     */
    public function saveTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_system' => 'required|string|max:255',
            'entity_type' => 'required|in:doctor,patient,patient_data,diagnosis',
            'mappings' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $mappings = $request->input('mappings');

        foreach ($mappings as $sourceColumn => $targetField) {
            if (!empty($targetField)) {
                DataMigrationMapping::updateOrCreate(
                    [
                        'source_system' => $request->input('source_system'),
                        'entity_type' => $request->input('entity_type'),
                        'source_column' => $sourceColumn,
                    ],
                    [
                        'target_field' => $targetField,
                        'confidence' => 1.0,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Template saved successfully.');
    }

    /**
     * Get the appropriate parser for the file type
     */
    protected function getParser(string $fileType): ?object
    {
        return match ($fileType) {
            'csv' => new CsvParser(),
            'xlsx', 'xls' => new ExcelParser(),
            'json' => new JsonParser(),
            'sql' => new SqlDumpParser(),
            default => null,
        };
    }
}
