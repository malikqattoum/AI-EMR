<?php

namespace App\Listeners;

use App\Events\KPIAlertTriggered;
use App\Notifications\KPIAlertNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyKPIAlertsListener implements ShouldQueue
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
    public function handle(KPIAlertTriggered $event): void
    {
        $alert = $event->alert;

        Log::info('KPI alert triggered, sending notifications', [
            'alert_id' => $alert->id ?? null,
            'kpi_name' => $alert->kpi_name ?? 'unknown',
        ]);

        try {
            // Notify admin/users about KPI alerts
            $admins = \App\Models\User::whereIn('role', ['admin', 'hospital_admin'])
                ->where('is_active', true)
                ->get();

            foreach ($admins as $admin) {
                $admin->notify(new KPIAlertNotification($alert));
            }

            Log::info('KPI alert notifications sent', [
                'alert_id' => $alert->id ?? null,
                'admin_count' => $admins->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send KPI alert notification', [
                'alert_id' => $alert->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
