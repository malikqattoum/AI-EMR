<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\AppointmentCancelledEvent::class => [
            \App\Listeners\ProcessWaitlistOnAppointmentCancellation::class,
        ],
        \App\Events\AppointmentCompletedEvent::class => [
            \App\Listeners\ProcessWaitlistOnAppointmentCompletion::class,
        ],
        \App\Events\AppointmentBookedEvent::class => [
            \App\Listeners\SendAppointmentConfirmationNotification::class,
        ],
        \App\Events\AppointmentStatusChangedEvent::class => [
            \App\Listeners\SendAppointmentStatusChangeNotification::class,
        ],
        \App\Events\DocumentCreated::class => [
            \App\Listeners\MonitorDocumentCreation::class,
        ],
        \App\Events\DocumentUpdated::class => [
            \App\Listeners\MonitorDocumentUpdate::class,
        ],
        \App\Events\DocumentSubmitted::class => [
            \App\Listeners\MonitorDocumentSubmission::class,
        ],
        \App\Events\ClinicalAlertTriggered::class => [
            \App\Listeners\NotifyClinicalAlertsListener::class,
        ],
        \App\Events\KPIAlertTriggered::class => [
            \App\Listeners\NotifyKPIAlertsListener::class,
        ],
        \App\Events\NewNotification::class => [
            \App\Listeners\BroadcastNewNotification::class,
        ],
        \App\Events\NotificationRead::class => [
            \App\Listeners\TrackNotificationRead::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        // Register the AppointmentObserver
        \App\Models\Appointment::observe(\App\Observers\AppointmentObserver::class);
    }
}
