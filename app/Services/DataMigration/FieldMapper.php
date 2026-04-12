<?php

namespace App\Services\DataMigration;

class FieldMapper
{
    private array $aliasMaps = [
        'patient' => [
            'name' => ['name', 'patient_name', 'full_name', 'fullname', 'patient', 'full_name', 'patientname'],
            'email' => ['email', 'email_address', 'patient_email', 'emailaddress', 'e_mail', 'mail'],
            'phone' => ['phone', 'phone_number', 'tel', 'mobile', 'contact_no', 'contact_number', 'telephone'],
            'date_of_birth' => ['dob', 'date_of_birth', 'birthdate', 'birth_date', 'dob', 'dateofbirth', 'bday'],
            'gender' => ['gender', 'sex', 'patient_sex'],
            'age' => ['age', 'patient_age'],
            'address' => ['address', 'patient_address', 'street_address', 'street', 'address_line_1'],
            'city' => ['city', 'patient_city'],
            'state' => ['state', 'patient_state', 'province'],
            'zip_code' => ['zip', 'zip_code', 'postal_code', 'pincode', 'zipcode', 'postal'],
            'blood_type' => ['blood_type', 'blood_group', 'bloodtype', 'bloodgrp'],
            'allergies' => ['allergies', 'allergy', 'allergic'],
        ],
        'doctor' => [
            'name' => ['name', 'doctor_name', 'full_name', 'fullname', 'doctor', 'full_name', 'doctorname'],
            'email' => ['email', 'email_address', 'doctor_email', 'emailaddress', 'e_mail'],
            'phone' => ['phone', 'phone_number', 'tel', 'mobile', 'contact_no', 'contact_number'],
            'specialty' => ['specialty', 'specialization', 'speciality', 'doctor_specialty', 'specialties'],
            'license_number' => ['license', 'license_number', 'licence', 'licence_number', 'medical_license'],
            'bio' => ['bio', 'biography', 'about', 'description', 'doctor_bio'],
            'consultation_fee' => ['fee', 'consultation_fee', 'consultation_charge', 'charge', 'price'],
            'languages' => ['languages', 'language', 'spoken_languages', 'tongue'],
        ],
        'patient_data' => [
            'name' => ['name', 'patient_name', 'full_name'],
            'weight' => ['weight', 'wt', 'body_weight'],
            'height' => ['height', 'ht', 'body_height'],
            'blood_pressure' => ['blood_pressure', 'bp', 'blood_pressure_reading'],
            'temperature' => ['temperature', 'temp', 'body_temp', 'body_temperature'],
            'symptoms' => ['symptoms', 'complaint', 'chief_complaint', 'presenting_complaint'],
            'diagnosis' => ['diagnosis', 'preliminary_diagnosis', 'assessment', 'impression'],
            'medications' => ['medications', 'medication_history', 'current_medications', 'meds'],
            'allergies' => ['allergies', 'allergy'],
            'notes' => ['notes', 'physician_notes', 'additional_notes', 'clinical_notes'],
        ],
        'diagnosis' => [
            'diagnosis_text' => ['diagnosis', 'diagnosis_text', 'diagnosis_description', 'assessment'],
            'patient_id' => ['patient_id', 'patientid', 'patient'],
            'doctor_id' => ['doctor_id', 'doctorid', 'doctor'],
            'follow_up_count' => ['follow_up_count', 'followups', 'follow_up_questions'],
        ],
    ];

    private const FUZZY_THRESHOLD = 0.7;

    public function autoMap(array $sourceColumns, string $entityType): array
    {
        return array_map(fn($col) => $this->findBestMapping($col, $entityType), $sourceColumns);
    }

    private function findBestMapping(string $sourceColumn, string $entityType): FieldMapping
    {
        $exactMatch = $this->findExactMatch($sourceColumn, $entityType);
        if ($exactMatch !== null) {
            return new FieldMapping($sourceColumn, $exactMatch, 1.0, false);
        }

        $fuzzyMatch = $this->findFuzzyMatch($sourceColumn, $entityType);
        if ($fuzzyMatch !== null) {
            [$targetField, $matchedAlias] = $fuzzyMatch;
            return new FieldMapping($sourceColumn, $targetField, $this->calculateSimilarity($sourceColumn, $matchedAlias), false);
        }

        return new FieldMapping($sourceColumn, '', 0.0, false);
    }

    private function findExactMatch(string $sourceColumn, string $entityType): ?string
    {
        $sourceLower = strtolower($sourceColumn);
        if (!isset($this->aliasMaps[$entityType])) {
            return null;
        }

        foreach ($this->aliasMaps[$entityType] as $targetField => $aliases) {
            if (in_array($sourceLower, array_map('strtolower', $aliases), true)) {
                return $targetField;
            }
        }

        return null;
    }

    private function findFuzzyMatch(string $sourceColumn, string $entityType): ?array
    {
        if (!isset($this->aliasMaps[$entityType])) {
            return null;
        }

        $bestMatch = null;
        $bestScore = 0;

        foreach ($this->aliasMaps[$entityType] as $targetField => $aliases) {
            foreach ($aliases as $alias) {
                $similarity = $this->calculateSimilarity($sourceColumn, $alias);
                if ($similarity > $bestScore && $similarity >= self::FUZZY_THRESHOLD) {
                    $bestScore = $similarity;
                    $bestMatch = [$targetField, $alias];
                }
            }
        }

        return $bestMatch;
    }

    public function getUnmappedColumns(array $mappings, array $sourceColumns, float $threshold = 0.5): array
    {
        $unmapped = array_filter($mappings, fn($m) => $m->confidence < $threshold);
        $mappedColumns = array_map(fn($m) => $m->sourceColumn, $mappings);
        foreach ($sourceColumns as $column) {
            if (!in_array($column, $mappedColumns, true)) {
                $unmapped[] = $column;
            }
        }
        return array_values($unmapped);
    }

    public function getSuggestedTargetField(string $sourceColumn, string $entityType): ?string
    {
        $exactMatch = $this->findExactMatch($sourceColumn, $entityType);
        if ($exactMatch !== null) {
            return $exactMatch;
        }
        $fuzzyMatch = $this->findFuzzyMatch($sourceColumn, $entityType);
        return $fuzzyMatch !== null ? $fuzzyMatch[0] : null;
    }

    private function calculateSimilarity(string $source, string $target): float
    {
        $sourceLower = strtolower($source);
        $targetLower = strtolower($target);
        if ($sourceLower === $targetLower) {
            return 1.0;
        }
        $distance = levenshtein($sourceLower, $targetLower);
        $maxLen = max(strlen($sourceLower), strlen($targetLower));
        return $maxLen === 0 ? 0.0 : 1 - ($distance / $maxLen);
    }

    public function applyMappingTemplate(string $sourceSystem, string $entityType): array
    {
        $mappings = \App\Models\DataMigrationMapping::where('source_system', $sourceSystem)
            ->where('entity_type', $entityType)
            ->get();

        return $mappings->map(fn($m) => new FieldMapping(
            sourceColumn: $m->source_column,
            targetField: $m->target_field,
            confidence: $m->confidence,
            confirmed: (bool) $m->confirmed
        ))->all();
    }
}
