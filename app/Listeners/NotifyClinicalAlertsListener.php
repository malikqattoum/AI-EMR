<?php

namespace App\Listeners;

use App\Events\ClinicalAlertTriggered;
use App\Notifications\ClinicalAlertNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyClinicalAlertsListener implements ShouldQueue
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
    public function handle(ClinicalAlertTriggered $event): void
    {
        $alert = $event->alert;

        Log::info('Clinical alert triggered, sending notifications', [
            'alert_id' => $alert->id ?? null,
            'alert_type' => $alert->alert_type ?? 'unknown',
            'patient_id' => $alert->patient_id ?? null,
        ]);

        try {
            // Notify relevant medical staff about the clinical alert
            // This could be expanded based on your notification preferences
            if ($alert->doctor_id) {
                $doctor = \App\Models\User::find($alert->doctor_id);
                if ($doctor) {
                    $doctor->notify(new ClinicalAlertNotification($alert));
                }
            }

            Log::info('Clinical alert notification sent', [
                'alert_id' => $alert->id ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send clinical alert notification', [
                'alert_id' => $alert->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
