<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Patient;
use App\Models\User;
use App\Models\VoiceTranscription;
use App\Models\AiAssistantResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsultationWizardController extends Controller
{
    /**
     * Show the consultation wizard view
     */
    public function index(Request $request)
    {
        $appointmentId = $request->get('appointment_id');
        $patientId = $request->get('patient_id');

        // Validate that doctor has access to this patient
        $doctor = Auth::user();
        $patient = Patient::where('id', $patientId)
            ->where(function ($query) use ($doctor) {
                $query->where('primary_doctor_id', $doctor->id)
                    ->orWhereIn('id', function ($q) use ($doctor) {
                        $q->select('patient_id')
                            ->from('appointments')
                            ->where('doctor_id', $doctor->id);
                    });
            })
            ->firstOrFail();

        // Get appointment if provided
        $appointment = null;
        if ($appointmentId) {
            $appointment = Appointment::where('id', $appointmentId)
                ->where('doctor_id', $doctor->id)
                ->first();
        }

        return view('doctor.consultation-wizard', compact('appointment', 'patient'));
    }

    /**
     * Get patient data for the wizard
     */
    public function getPatientData($patientId)
    {
        $doctor = Auth::user();

        $patient = Patient::where('id', $patientId)
            ->where(function ($query) use ($doctor) {
                $query->where('primary_doctor_id', $doctor->id)
                    ->orWhereIn('id', function ($q) use ($doctor) {
                        $q->select('patient_id')
                            ->from('appointments')
                            ->where('doctor_id', $doctor->id);
                    });
            })
            ->with(['patientRiskScores' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->firstOrFail();

        // Calculate age if DOB exists
        if ($patient->date_of_birth) {
            $patient->age = \Carbon\Carbon::parse($patient->date_of_birth)->age;
        }

        // Get risk score
        $riskScore = $patient->patientRiskScores->first();
        if ($riskScore) {
            $patient->risk_score = max($riskScore->no_show_risk ?? 0, $riskScore->hospitalization_risk ?? 0);
        }

        return response()->json($patient);
    }

    /**
     * Get AI case summary for patient
     */
    public function getAICaseSummary($patientId)
    {
        $doctor = Auth::user();

        // Verify patient access
        $patient = Patient::where('id', $patientId)
            ->where(function ($query) use ($doctor) {
                $query->where('primary_doctor_id', $doctor->id)
                    ->orWhereIn('id', function ($q) use ($doctor) {
                        $q->select('patient_id')
                            ->from('appointments')
                            ->where('doctor_id', $doctor->id);
                    });
            })
            ->firstOrFail();

        // Get latest AI analysis for this patient
        $latestAnalysis = AiAssistantResult::where('patient_id', $patientId)
            ->where('doctor_id', $doctor->id)
            ->where('source', 'voice_assistant')
            ->latest()
            ->first();

        if (!$latestAnalysis) {
            return response()->json([]);
        }

        return response()->json([$latestAnalysis]);
    }

    /**
     * Save wizard draft progress
     */
    public function saveDraft(Request $request)
    {
        $doctor = Auth::user();

        $validated = $request->validate([
            'appointment_id' => 'nullable|integer',
            'patient_id' => 'required|integer',
            'session_id' => 'nullable|string',
            'current_step' => 'required|integer|min:1|max:5',
            'clinical_chart' => 'nullable|array',
            'diagnosis_text' => 'nullable|string',
        ]);

        // Store draft in session/cache for recovery
        $draftKey = "wizard_draft_{$doctor->id}_{$validated['patient_id']}";
        cache()->put($draftKey, $validated, now()->addHours(2));

        return response()->json([
            'success' => true,
            'message' => 'Draft saved successfully'
        ]);
    }

    /**
     * Load saved draft
     */
    public function loadDraft(Request $request)
    {
        $doctor = Auth::user();
        $patientId = $request->get('patient_id');

        $draftKey = "wizard_draft_{$doctor->id}_{$patientId}";
        $draft = cache()->get($draftKey);

        return response()->json($draft ?? null);
    }

    /**
     * Complete consultation from wizard
     * Reuses existing VoiceAssistantController logic
     */
    public function completeConsultation(Request $request)
    {
        $doctor = Auth::user();

        $validated = $request->validate([
            'appointment_id' => 'nullable|integer',
            'patient_id' => 'required|integer',
            'session_id' => 'required|string',
            'diagnosis_text' => 'required|string|min:10',
            'clinical_chart' => 'nullable|array',
            'completion_type' => 'required|in:completed,referral,followup',
            'followup_date' => 'nullable|date|after:now',
            'send_summary_to_patient' => 'boolean',
            'ai_result_id' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            // Verify patient access
            $patient = Patient::where('id', $validated['patient_id'])
                ->where(function ($query) use ($doctor) {
                    $query->where('primary_doctor_id', $doctor->id)
                        ->orWhereIn('id', function ($q) use ($doctor) {
                            $q->select('patient_id')
                                ->from('appointments')
                                ->where('doctor_id', $doctor->id);
                        });
                })
                ->firstOrFail();

            // Handle appointment
            $appointment = null;
            if ($validated['appointment_id']) {
                $appointment = Appointment::where('id', $validated['appointment_id'])
                    ->where('doctor_id', $doctor->id)
                    ->firstOrFail();

                // Mark appointment as completed
                $appointment->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            } else {
                // Create walk-in appointment
                $appointment = Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $validated['patient_id'],
                    'appointment_date' => now(),
                    'status' => 'completed',
                    'completed_at' => now(),
                    'appointment_type' => 'walk-in',
                ]);
            }

            // Create diagnosis record
            $diagnosis = Diagnosis::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $validated['patient_id'],
                'appointment_id' => $appointment->id,
                'diagnosis_text' => $validated['diagnosis_text'],
                'patient_data' => $validated['clinical_chart'] ?? [],
                'voice_transcripts' => [$this->getTranscript($validated['session_id'])],
            ]);

            // Link AI result if provided
            if ($validated['ai_result_id']) {
                $aiResult = AiAssistantResult::find($validated['ai_result_id']);
                if ($aiResult) {
                    $aiResult->update([
                        'status' => 'approved',
                        'diagnosis_id' => $diagnosis->id,
                    ]);
                }
            }

            // Create follow-up appointment if requested
            if ($validated['followup_date']) {
                Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $validated['patient_id'],
                    'appointment_date' => $validated['followup_date'],
                    'status' => 'scheduled',
                    'appointment_type' => 'follow-up',
                    'notes' => "Follow-up for consultation on " . now()->format('Y-m-d'),
                ]);
            }

            // Send summary to patient if requested
            if ($validated['send_summary_to_patient']) {
                $this->sendPatientSummary($patient, $diagnosis, $appointment);
            }

            // Update voice transcription status
            VoiceTranscription::where('session_id', $validated['session_id'])
                ->update([
                    'status' => 'diagnosis_created',
                    'diagnosis_id' => $diagnosis->id,
                ]);

            DB::commit();

            // Return redirect URL
            return response()->json([
                'success' => true,
                'message' => 'Consultation completed successfully',
                'redirect_url' => route('doctor.dashboard'),
                'diagnosis_id' => $diagnosis->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wizard consultation completion failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to complete consultation',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get transcription text from session
     */
    protected function getTranscript($sessionId)
    {
        $transcription = VoiceTranscription::where('session_id', $sessionId)->first();
        return $transcription?->raw_transcription ?? '';
    }

    /**
     * Send summary to patient (notification/email)
     */
    protected function sendPatientSummary($patient, $diagnosis, $appointment)
    {
        // This would trigger patient notification
        // For now, just log it
        Log::info("Patient summary sent to {$patient->name}", [
            'diagnosis_id' => $diagnosis->id,
            'appointment_id' => $appointment->id,
        ]);
    }
}
