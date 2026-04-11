<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\PatientRiskScore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Service for managing patient risk predictions.
 * 
 * Handles generation, caching, and retrieval of risk predictions
 * for appointments.
 */
class RiskPredictionService extends BaseService
{
    /**
     * Ensure risk predictions exist for an appointment.
     * Generates them if they don't exist.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function ensurePredictionsExist(Appointment $appointment): void
    {
        // Skip if no patient associated
        if (!$appointment->patient_id) {
            return;
        }

        $cacheKey = "risk_predictions_{$appointment->patient_id}_{$appointment->id}";

        // Check cache first
        if (Cache::has($cacheKey)) {
            return; // Already processed recently
        }

        // Check if risk score already exists for this appointment
        $existingRiskScore = PatientRiskScore::where('patient_id', $appointment->patient_id)
            ->where('appointment_id', $appointment->id)
            ->first();

        if ($existingRiskScore) {
            // Cache for 1 hour to prevent repeated checks
            Cache::put($cacheKey, true, 3600);
            return; // Already exists
        }

        try {
            // Generate predictions using the service
            $predictiveService = app(PredictiveAnalyticsService::class);
            $predictions = $predictiveService->predictRisks($appointment->patient, $appointment);

            // Create and save the risk score
            PatientRiskScore::create([
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'no_show_risk' => $predictions['no_show_risk'],
                'hospitalization_risk' => $predictions['hospitalization_risk'],
            ]);

            // Cache success for 1 hour
            Cache::put($cacheKey, true, 3600);

        } catch (Exception $e) {
            // Log error but don't fail the page load
            $this->logError('Failed to generate risk predictions for appointment', [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'error' => $e->getMessage(),
            ], $e);

            // Cache failure for 5 minutes to avoid repeated attempts
            Cache::put($cacheKey, false, 300);
        }
    }

    /**
     * Get risk predictions for an appointment.
     *
     * @param Appointment $appointment
     * @return PatientRiskScore|null
     */
    public function getPredictions(Appointment $appointment): ?PatientRiskScore
    {
        return PatientRiskScore::where('patient_id', $appointment->patient_id)
            ->where('appointment_id', $appointment->id)
            ->first();
    }

    /**
     * Clear risk prediction cache for an appointment.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function clearCache(Appointment $appointment): void
    {
        $cacheKey = "risk_predictions_{$appointment->patient_id}_{$appointment->id}";
        Cache::forget($cacheKey);
    }
}
