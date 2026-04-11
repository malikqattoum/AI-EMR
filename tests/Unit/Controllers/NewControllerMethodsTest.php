<?php

namespace Tests\Unit\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\VoiceTranscription;
use App\Models\PatientData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class NewControllerMethodsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $doctor;
    protected $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'doctor',
        ]);

        $specialty = Specialty::factory()->create();
        $this->doctor = Doctor::factory()->create([
            'user_id' => $this->user->id,
            'specialty_id' => $specialty->id,
            'is_active' => true,
        ]);

        $this->patient = User::factory()->create([
            'role' => 'patient',
            'name' => 'Test Patient',
            'age' => 35,
            'gender' => 'male',
        ]);

        $this->actingAs($this->user);
    }

    /**
     * Test VoiceAssistantController::savePostRecordingDiagnosis
     */
    public function test_save_post_recording_diagnosis_validates_input()
    {
        $response = $this->postJson(route('ai.ambient-listening.save-post-recording-diagnosis'), [
            'sessionId' => 'test-session-123',
        ]);

        // Validation should fail (missing required fields)
        $this->assertTrue(in_array($response->status(), [422, 403, 302]));
    }

    public function test_save_post_recording_diagnosis_requires_patient_access()
    {
        $sessionId = 'test-session-' . uniqid();

        // Create a voice transcription for this doctor
        VoiceTranscription::create([
            'session_id' => $sessionId,
            'doctor_id' => $this->user->id,
            'status' => 'completed',
            'raw_transcription' => 'Test transcription',
        ]);

        // Try to save diagnosis for a patient not assigned to this doctor
        $response = $this->postJson(route('ai.ambient-listening.save-post-recording-diagnosis'), [
            'diagnosisText' => 'Test diagnosis',
            'selectedPatient' => $this->patient->id,
            'transcription' => 'Test transcription',
            'sessionId' => $sessionId,
        ]);

        // Should fail if patient not assigned (403 or 422)
        $this->assertTrue(in_array($response->status(), [403, 422]));
    }

    /**
     * Test OpenAIController::getResponse
     */
    public function test_get_response_requires_symptoms()
    {
        $response = $this->postJson(route('ai.respond'), [
            'patient_id' => $this->patient->id,
        ]);

        $this->assertTrue(in_array($response->status(), [422, 302]));
    }

    /**
     * Test OpenAIController::followUp
     */
    public function test_follow_up_requires_previous_analysis()
    {
        $response = $this->postJson(route('ai.follow-up'), [
            'new_information' => 'New info',
        ]);

        $this->assertTrue(in_array($response->status(), [422, 302]));
    }

    /**
     * Test OpenAIController::createManualDiagnosis
     */
    public function test_create_manual_diagnosis_requires_patient_id()
    {
        $response = $this->postJson(route('ai.create-manual-diagnosis'), [
            'symptoms' => 'Headache',
            'diagnosis_text' => 'Tension headache',
        ]);

        $this->assertTrue(in_array($response->status(), [422, 302]));
    }
}
