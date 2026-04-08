<?php

namespace App\Jobs;

use App\Models\Diagnosis;
use App\Models\HepProgram;
use App\Models\User;
use App\Services\HEPGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class GenerateHEPProgram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Diagnosis $diagnosis;
    protected User $patient;
    protected User $doctor;
    protected array $additionalContext;

    /**
     * Create a new job instance.
     */
    public function __construct(
        Diagnosis $diagnosis,
        User $patient,
        User $doctor,
        array $additionalContext = []
    ) {
        $this->diagnosis = $diagnosis;
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->additionalContext = $additionalContext;
    }

    /**
     * Execute the job.
     */
    public function handle(HEPGenerator $hepGenerator): void
    {
        Log::info('Starting HEP program generation job', [
            'diagnosis_id' => $this->diagnosis->id,
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
        ]);

        try {
            // Generate the HEP program
            $program = $hepGenerator->generateProgram(
                $this->diagnosis,
                $this->patient,
                $this->doctor,
                $this->additionalContext
            );

            Log::info('HEP program generation job completed successfully', [
                'program_id' => $program->id,
                'diagnosis_id' => $this->diagnosis->id,
                'patient_id' => $this->patient->id,
            ]);

            // Notify the doctor that the HEP program is ready
            // You can implement this notification as needed
            // Notification::send($this->doctor, new HEPProgramGenerated($program));

        } catch (\Exception $e) {
            Log::error('HEP program generation job failed', [
                'diagnosis_id' => $this->diagnosis->id,
                'patient_id' => $this->patient->id,
                'doctor_id' => $this->doctor->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateHEPProgram job failed after retries', [
            'diagnosis_id' => $this->diagnosis->id,
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'error' => $exception->getMessage(),
        ]);

        // Optionally notify the doctor about the failure
        // Notification::send($this->doctor, new HEPProgramGenerationFailed($exception));
    }
}
