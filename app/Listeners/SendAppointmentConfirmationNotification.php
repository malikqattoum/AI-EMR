<?php

namespace App\Listeners;

use App\Events\AppointmentBookedEvent;
use App\Notifications\AppointmentConfirmationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendAppointmentConfirmationNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentBookedEvent $event): void
    {
        $appointment = $event->appointment;

        Log::info('Sending appointment confirmation', [
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
        ]);

        try {
            // Send notification to patient
            if ($appointment->patient) {
                $appointment->patient->notify(
                    new AppointmentConfirmationNotification($appointment)
                );
            }

            // Send notification to doctor
            if ($appointment->doctor) {
                $appointment->doctor->notify(
                    new AppointmentConfirmationNotification($appointment)
                );
            }

            Log::info('Appointment confirmation sent successfully', [
                'appointment_id' => $appointment->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send appointment confirmation', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
