<?php

namespace App\Listeners;

use App\Events\NotificationRead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class TrackNotificationRead implements ShouldQueue
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
    public function handle(NotificationRead $event): void
    {
        Log::info('Notification marked as read', [
            'user_id' => $event->userId,
            'notification_id' => $event->notificationId,
        ]);

        // Update notification read status in database if needed
        // This can be expanded based on your tracking requirements
    }
}
