<?php

namespace App\Services\DataMigration;

use App\Models\User;
use App\Models\Doctor;
use App\Models\PatientData;
use App\Models\Diagnosis;
use App\Models\Specialty;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataImporter
{
    public function importDoctors(array $records, int $hospitalId, string $sourceSystem): ImportResult
    {
        $result = new ImportResult();

        foreach ($records as $record) {
            if (!$record->valid) {
                $result->addFailure($record->rowNumber, 'Invalid record: ' . $record->validationError, $record->data);
                continue;
            }

            if (empty($record->data['name']) || empty($record->data['email'])) {
                $result->addFailure($record->rowNumber, 'Missing required fields (name, email)', $record->data);
                continue;
            }

            try {
                DB::beginTransaction();

                if ($this->doctorExists($record)) {
                    $result->incrementSkipped();
                    DB::rollBack();
                    continue;
                }

                $user = User::create([
                    'name' => $record->data['name'] ?? null,
                    'email' => $record->data['email'] ?? null,
                    'phone' => $record->data['phone'] ?? null,
                    'role' => 'doctor',
                    'hospital_id' => $hospitalId,
                    'password' => bcrypt('migration_' . now()->timestamp),
                    'source_identifiers' => [
                        'source_system' => $sourceSystem,
                        'source_id' => $record->sourceIds['source_id'] ?? null,
                    ],
                ]);

                $specialtyId = $record->data['specialty_id'] ?? null;
                if (!$specialtyId) {
                    $specialty = Specialty::firstOrCreate(
                        ['name' => 'General Practice'],
                        ['description' => 'Default specialty for migrated doctors']
                    );
                    $specialtyId = $specialty->id;
                }

                Doctor::create([
                    'user_id' => $user->id,
                    'phone' => $record->data['phone'] ?? null,
                    'license_number' => $record->data['license_number'] ?? ('MIG-' . $user->id . '-' . time()),
                    'bio' => $record->data['bio'] ?? null,
                    'languages' => $this->parseJsonField($record->data['languages'] ?? null),
                    'consultation_fee' => $this->parseFee($record->data['consultation_fee'] ?? null),
                    'specialty_id' => $specialtyId,
                ]);

                $result->incrementImported();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $result->addFailure($record->rowNumber, $e->getMessage(), $record->data);
            }
        }

        return $result;
    }

    public function importPatients(array $records, int $hospitalId, string $sourceSystem): ImportResult
    {
        $result = new ImportResult();

        foreach ($records as $record) {
            if (!$record->valid) {
                $result->addFailure($record->rowNumber, 'Invalid record: ' . $record->validationError, $record->data);
                continue;
            }

            if (empty($record->data['name']) || empty($record->data['email'])) {
                $result->addFailure($record->rowNumber, 'Missing required fields (name, email)', $record->data);
                continue;
            }

            try {
                DB::beginTransaction();

                if ($this->patientExists($record)) {
                    $result->incrementSkipped();
                    DB::rollBack();
                    continue;
                }

                User::create([
                    'name' => $record->data['name'] ?? null,
                    'email' => $record->data['email'] ?? null,
                    'phone' => $record->data['phone'] ?? null,
                    'role' => 'patient',
                    'password' => bcrypt('migration_' . now()->timestamp),
                    'date_of_birth' => $this->parseDate($record->data['date_of_birth'] ?? null),
                    'gender' => $record->data['gender'] ?? null,
                    'age' => $record->data['age'] ?? null,
                    'address' => $record->data['address'] ?? null,
                    'city' => $record->data['city'] ?? null,
                    'state' => $record->data['state'] ?? null,
                    'zip_code' => $record->data['zip_code'] ?? null,
                    'hospital_id' => $hospitalId,
                    'source_identifiers' => [
                        'source_system' => $sourceSystem,
                        'source_id' => $record->sourceIds['source_id'] ?? null,
                    ],
                ]);

                $result->incrementImported();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $result->addFailure($record->rowNumber, $e->getMessage(), $record->data);
            }
        }

        return $result;
    }

    public function importPatientData(array $records, int $hospitalId, string $sourceSystem): ImportResult
    {
        $result = new ImportResult();

        foreach ($records as $record) {
            if (!$record->valid) {
                $result->addFailure($record->rowNumber, 'Invalid record: ' . $record->validationError, $record->data);
                continue;
            }

            if (empty($record->data['name'])) {
                $result->addFailure($record->rowNumber, 'Missing required field: name', $record->data);
                continue;
            }

            try {
                DB::beginTransaction();

                $patient = $this->findPatientBySourceId($record->sourceIds['patient_source_id'] ?? null, $sourceSystem);

                if (!$patient && !empty($record->data['phone'])) {
                    $patient = User::where('role', 'patient')
                        ->where('name', $record->data['name'])
                        ->where('phone', $record->data['phone'])
                        ->first();
                }

                if (!$patient) {
                    $result->addFailure($record->rowNumber, 'Patient not found for linking', $record->data);
                    DB::rollBack();
                    continue;
                }

                PatientData::create([
                    'name' => $record->data['name'] ?? null,
                    'weight' => $record->data['weight'] ?? null,
                    'height' => $record->data['height'] ?? null,
                    'blood_pressure' => $record->data['blood_pressure'] ?? null,
                    'temperature' => $record->data['temperature'] ?? null,
                    'symptoms' => $this->parseJsonField($record->data['symptoms'] ?? null),
                    'allergies' => $record->data['allergies'] ?? null,
                    'notes' => $record->data['notes'] ?? null,
                    'user_id' => $patient->id,
                    'source_record_id' => $record->sourceIds['source_id'] ?? null,
                    'age' => $record->data['age'] ?? null,
                    'gender' => $record->data['gender'] ?? null,
                ]);

                $result->incrementImported();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $result->addFailure($record->rowNumber, $e->getMessage(), $record->data);
            }
        }

        return $result;
    }

    public function importDiagnoses(array $records, int $hospitalId, string $sourceSystem): ImportResult
    {
        $result = new ImportResult();

        foreach ($records as $record) {
            if (!$record->valid) {
                $result->addFailure($record->rowNumber, 'Invalid record: ' . $record->validationError, $record->data);
                continue;
            }

            if (empty($record->data['diagnosis_text'])) {
                $result->addFailure($record->rowNumber, 'Missing required field: diagnosis_text', $record->data);
                continue;
            }

            try {
                DB::beginTransaction();

                $patient = $this->findPatientBySourceId($record->sourceIds['patient_source_id'] ?? null, $sourceSystem);

                if (!$patient && !empty($record->data['patient_name']) && !empty($record->data['patient_phone'])) {
                    $patient = User::where('role', 'patient')
                        ->where('name', $record->data['patient_name'])
                        ->where('phone', $record->data['patient_phone'])
                        ->first();
                }

                if (!$patient && !empty($record->data['patient_name'])) {
                    $patient = User::where('role', 'patient')
                        ->where('name', $record->data['patient_name'])
                        ->first();
                }

                if (!$patient) {
                    $result->addFailure($record->rowNumber, 'Patient not found for diagnosis', $record->data);
                    DB::rollBack();
                    continue;
                }

                $doctor = $this->findDoctorBySourceId($record->sourceIds['doctor_source_id'] ?? null, $sourceSystem);

                if (!$doctor && !empty($record->data['doctor_name']) && !empty($record->data['doctor_phone'])) {
                    $doctor = User::where('role', 'doctor')
                        ->where('name', $record->data['doctor_name'])
                        ->where('phone', $record->data['doctor_phone'])
                        ->first();
                }

                if (!$doctor && !empty($record->data['doctor_name'])) {
                    $doctor = User::where('role', 'doctor')
                        ->where('name', $record->data['doctor_name'])
                        ->first();
                }

                if (!$doctor) {
                    $result->addFailure($record->rowNumber, 'Doctor not found for diagnosis', $record->data);
                    DB::rollBack();
                    continue;
                }

                Diagnosis::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patient->id,
                    'diagnosis_text' => $record->data['diagnosis_text'] ?? null,
                    'follow_up_count' => $record->data['follow_up_count'] ?? 0,
                    'source_record_id' => $record->sourceIds['source_id'] ?? null,
                ]);

                $result->incrementImported();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $result->addFailure($record->rowNumber, $e->getMessage(), $record->data);
            }
        }

        return $result;
    }

    protected function userExists(string $role, NormalizedRecord $record): bool
    {
        return User::where('role', $role)
            ->where(function ($q) use ($record) {
                $q->where('email', $record->data['email'] ?? null)
                  ->orWhere(function ($q2) use ($record) {
                      $q2->where('name', $record->data['name'] ?? null)
                         ->where('phone', $record->data['phone'] ?? null);
                  });
            })
            ->exists();
    }

    protected function doctorExists(NormalizedRecord $record): bool
    {
        return $this->userExists('doctor', $record);
    }

    protected function patientExists(NormalizedRecord $record): bool
    {
        return $this->userExists('patient', $record);
    }

    protected function findUserBySourceId(?string $sourceId, string $sourceSystem, string $role): ?User
    {
        if (!$sourceId) {
            return null;
        }

        return User::where('role', $role)
            ->whereJsonContains('source_identifiers', ['source_system' => $sourceSystem, 'source_id' => $sourceId])
            ->first();
    }

    protected function findPatientBySourceId(?string $sourceId, string $sourceSystem): ?User
    {
        return $this->findUserBySourceId($sourceId, $sourceSystem, 'patient');
    }

    protected function findDoctorBySourceId(?string $sourceId, string $sourceSystem): ?User
    {
        return $this->findUserBySourceId($sourceId, $sourceSystem, 'doctor');
    }

    protected function parseJsonField(mixed $value): ?array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }

        return null;
    }

    protected function parseDate(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function parseFee(mixed $value): int
    {
        if (is_numeric($value)) {
            return (int) round($value * 100);
        }

        if (is_string($value)) {
            $cleaned = preg_replace('/[^0-9.]/', '', $value);
            return (int) round(floatval($cleaned) * 100);
        }

        return 0;
    }
}
