<?php

namespace App\Services;

use App\Models\Appointment;
use App\Mail\AppointmentConfirmationMail;
use App\Mail\AppointmentCancellationMail;
use App\Mail\AppointmentCompletionMail;
use App\Mail\FollowUpAppointmentMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

/**
 * Service for handling appointment email notifications.
 * 
 * Centralizes all appointment-related email sending to eliminate
 * code duplication across controllers.
 */
class AppointmentEmailService extends BaseService
{
    /**
     * Send appointment confirmation email.
     *
     * @param Appointment $appointment
     * @return bool
     */
    public function sendConfirmation(Appointment $appointment): bool
    {
        return $this->sendAppointmentEmail(
            appointment: $appointment,
            mailable: new AppointmentConfirmationMail($appointment),
            emailType: 'confirmation',
            logContext: [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient?->id,
                'doctor_id' => $appointment->doctor_id,
            ]
        );
    }

    /**
     * Send appointment cancellation email.
     *
     * @param Appointment $appointment
     * @param string $reason
     * @return bool
     */
    public function sendCancellation(Appointment $appointment, string $reason): bool
    {
        return $this->sendAppointmentEmail(
            appointment: $appointment,
            mailable: new AppointmentCancellationMail($appointment, $reason),
            emailType: 'cancellation',
            logContext: [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient?->id,
                'doctor_id' => $appointment->doctor_id,
                'cancellation_reason' => $reason,
            ]
        );
    }

    /**
     * Send appointment completion email.
     *
     * @param Appointment $appointment
     * @param mixed $diagnosis
     * @return bool
     */
    public function sendCompletion(Appointment $appointment, $diagnosis = null): bool
    {
        return $this->sendAppointmentEmail(
            appointment: $appointment,
            mailable: new AppointmentCompletionMail($appointment, $diagnosis),
            emailType: 'completion',
            logContext: [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient?->id,
                'doctor_id' => $appointment->doctor_id,
                'diagnosis_id' => $appointment->diagnosis_id,
            ]
        );
    }

    /**
     * Send follow-up appointment email.
     *
     * @param Appointment $followUpAppointment
     * @param Appointment $originalAppointment
     * @return bool
     */
    public function sendFollowUp(Appointment $followUpAppointment, Appointment $originalAppointment): bool
    {
        return $this->sendAppointmentEmail(
            appointment: $followUpAppointment,
            mailable: new FollowUpAppointmentMail($followUpAppointment, $originalAppointment),
            emailType: 'follow-up',
            logContext: [
                'follow_up_appointment_id' => $followUpAppointment->id,
                'original_appointment_id' => $originalAppointment->id,
                'patient_id' => $followUpAppointment->patient?->id,
                'doctor_id' => $followUpAppointment->doctor_id,
            ]
        );
    }

    /**
     * Generic method to send appointment email with error handling.
     *
     * @param Appointment $appointment
     * @param object $mailable
     * @param string $emailType
     * @param array $logContext
     * @return bool
     */
    protected function sendAppointmentEmail(
        Appointment $appointment,
        object $mailable,
        string $emailType,
        array $logContext = []
    ): bool {
        // Load patient relationship
        $appointment->load('patient');

        // Determine email recipient (patient email or guest email)
        $email = $appointment->patient?->email ?? $appointment->guest_email ?? null;
        
        if (!$email) {
            $this->logWarning("Cannot send appointment {$emailType} email - no email address", array_merge($logContext, [
                'has_patient' => $appointment->patient ? true : false,
                'patient_id' => $appointment->patient?->id,
                'guest_appointment' => $appointment->isGuestAppointment(),
                'guest_email' => $appointment->guest_email,
            ]));

            return false;
        }

        $this->logInfo("Sending appointment {$emailType} email", array_merge($logContext, [
            'recipient_email' => $email,
            'appointment_date' => $appointment->appointment_date,
            'status' => $appointment->status,
        ]));

        try {
            Mail::to($email)->send($mailable);
            
            $this->logInfo("Appointment {$emailType} email sent successfully", [
                'appointment_id' => $appointment->id,
                'recipient_email' => $email,
            ]);
            
            return true;
        } catch (Exception $e) {
            $this->logError("Failed to send appointment {$emailType} email", array_merge($logContext, [
                'patient_email' => $appointment->patient->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]), $e);
            
            // Return false but don't throw - email failures shouldn't break the flow
            return false;
        }
    }
}
