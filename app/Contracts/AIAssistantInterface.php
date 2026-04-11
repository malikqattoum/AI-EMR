<?php

namespace App\Contracts;

use App\Models\Appointment;

/**
 * Interface for AI assistant services.
 * 
 * This interface allows swapping between different AI providers
 * (OpenAI, Anthropic, etc.) and facilitates testing with mocks.
 */
interface AIAssistantInterface
{
    /**
     * Generate prescription suggestions based on appointment data.
     *
     * @param Appointment $appointment The appointment context
     * @param array $symptoms Patient symptoms
     * @param array $allergies Patient allergies
     * @param array $pastMeds Past medications
     * @param array $additionalData Additional context data
     * @return array
     */
    public function generatePrescriptionSuggestions(
        Appointment $appointment,
        array $symptoms,
        array $allergies,
        array $pastMeds,
        array $additionalData = []
    ): array;

    /**
     * Generate clinical notes from appointment data.
     *
     * @param Appointment $appointment The appointment context
     * @param array $data Clinical data
     * @return array
     */
    public function generateClinicalNotes(Appointment $appointment, array $data): array;

    /**
     * Analyze patient risk factors.
     *
     * @param int $patientId The patient ID
     * @return array
     */
    public function analyzePatientRisk(int $patientId): array;

    /**
     * Generate treatment recommendations.
     *
     * @param int $patientId The patient ID
     * @param array $context Treatment context
     * @return array
     */
    public function generateTreatmentRecommendations(int $patientId, array $context): array;

    /**
     * Summarize medical records.
     *
     * @param array $records Medical records to summarize
     * @return string
     */
    public function summarizeMedicalRecords(array $records): string;

    /**
     * Check if the AI service is available and configured.
     *
     * @return bool
     */
    public function isAvailable(): bool;
}
