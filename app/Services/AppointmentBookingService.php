<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

/**
 * Service for handling appointment booking operations.
 * 
 * Extracts appointment creation, validation, and patient creation
 * logic from controllers into a dedicated service.
 */
class AppointmentBookingService extends BaseService
{
    /**
     * Create a new appointment for an existing patient.
     *
     * @param Doctor $doctor
     * @param array $data Appointment data
     * @return array
     */
    public function bookForExistingPatient(Doctor $doctor, array $data): array
    {
        try {
            return $this->transaction(function () use ($doctor, $data) {
                $patient = User::findOrFail($data['patient_id']);
                
                $appointment = $this->createAppointment(
                    doctor: $doctor,
                    patient: $patient,
                    appointmentDate: Carbon::parse($data['appointment_date']),
                    appointmentType: $data['appointment_type'],
                    reason: $data['reason'],
                );

                return $this->successResult(
                    'Appointment booked successfully!',
                    $appointment
                );
            });
        } catch (Exception $e) {
            $this->logError('Failed to book appointment for existing patient', [], $e);
            
            return $this->errorResult('Failed to book appointment', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create a new appointment for a new patient.
     * Creates the patient account first.
     *
     * @param Doctor $doctor
     * @param array $data Appointment and patient data
     * @return array
     */
    public function bookForNewPatient(Doctor $doctor, array $data): array
    {
        try {
            return $this->transaction(function () use ($doctor, $data) {
                // Create patient account
                $patient = $this->createPatient($doctor, $data);

                // Create appointment
                $appointment = $this->createAppointment(
                    doctor: $doctor,
                    patient: $patient,
                    appointmentDate: Carbon::parse($data['appointment_date']),
                    appointmentType: $data['appointment_type'],
                    reason: $data['reason'],
                );

                return $this->successResult(
                    'Appointment booked successfully!',
                    compact('appointment', 'patient')
                );
            });
        } catch (Exception $e) {
            $this->logError('Failed to book appointment for new patient', [], $e);
            
            return $this->errorResult('Failed to book appointment', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Validate appointment slot availability.
     *
     * @param Doctor $doctor
     * @param string $appointmentDate
     * @return array
     */
    public function validateSlot(Doctor $doctor, string $appointmentDate): array
    {
        $appointmentDateTime = Carbon::parse($appointmentDate);
        $slots = $doctor->getAvailableSlots($appointmentDateTime->format('Y-m-d'));
        
        $requestedSlot = $slots->first(
            fn($slot) => $slot['datetime'] === $appointmentDateTime->toDateTimeString()
        );

        if (!$requestedSlot) {
            return [
                'valid' => false,
                'error' => 'Selected time slot is not available.',
            ];
        }

        return [
            'valid' => true,
            'slot' => $requestedSlot,
        ];
    }

    /**
     * Create an appointment instance.
     *
     * @param Doctor $doctor
     * @param User $patient
     * @param Carbon $appointmentDate
     * @param string $appointmentType
     * @param string $reason
     * @return Appointment
     */
    protected function createAppointment(
        Doctor $doctor,
        User $patient,
        Carbon $appointmentDate,
        string $appointmentType,
        string $reason
    ): Appointment {
        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'appointment_date' => $appointmentDate,
            'appointment_end' => $appointmentDate->copy()->addMinutes($doctor->appointment_duration),
            'status' => $doctor->auto_approve_appointments ? 'confirmed' : 'pending',
            'appointment_type' => $appointmentType,
            'reason' => $reason,
            'consultation_fee' => $doctor->consultation_fee,
        ]);

        // Confirm appointment if auto-approve is enabled
        if ($doctor->auto_approve_appointments) {
            $appointment->confirm();
        }

        $this->logInfo('Appointment created', [
            'appointment_id' => $appointment->id,
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => $appointment->patient_id,
            'status' => $appointment->status,
            'auto_approve' => $doctor->auto_approve_appointments,
        ]);

        return $appointment;
    }

    /**
     * Create a new patient account.
     *
     * @param Doctor $doctor
     * @param array $data Patient data
     * @return User
     */
    protected function createPatient(Doctor $doctor, array $data): User
    {
        // Auto-generate a secure password
        $generatedPassword = Str::random(12);

        // Calculate age from date of birth
        $birthDate = Carbon::parse($data['patient_date_of_birth']);
        $age = $birthDate->age;

        $patient = User::create([
            'name' => $data['patient_name'],
            'email' => $data['patient_email'],
            'phone' => $data['patient_phone'],
            'date_of_birth' => $data['patient_date_of_birth'],
            'age' => $age,
            'gender' => $data['patient_gender'],
            'password' => bcrypt($generatedPassword),
            'role' => 'patient',
            'email_verified_at' => now(), // Auto-verify since created by doctor
            'primary_doctor_id' => $doctor->user_id,
        ]);

        // Send welcome notification with login credentials
        $this->sendWelcomeNotification($patient, $generatedPassword, $doctor);

        return $patient;
    }

    /**
     * Send welcome notification to new patient.
     *
     * @param User $patient
     * @param string $password
     * @param Doctor $doctor
     * @return void
     */
    protected function sendWelcomeNotification(User $patient, string $password, Doctor $doctor): void
    {
        try {
            $patient->notify(new \App\Notifications\SystemAlertNotification(
                'Welcome to Our Medical Portal',
                "Your patient account has been created by Dr. {$doctor->user->name}.\n\n" .
                "Login Email: {$patient->email}\n" .
                "Temporary Password: {$password}\n\n" .
                "Please log in and change your password. You can manage your appointments and health records.",
                'success',
                [
                    'link' => route('login'),
                    'link_text' => 'Sign In to Your Account'
                ]
            ));
        } catch (Exception $e) {
            $this->logError('Failed to send welcome notification to new patient', [
                'patient_id' => $patient->id,
            ], $e);
        }
    }
}
