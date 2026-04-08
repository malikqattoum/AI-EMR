<?php

namespace App\Listeners;

use App\Events\NewNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BroadcastNewNotification implements ShouldQueue
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
    public function handle(NewNotification $event): void
    {
        $notification = $event->notification;
        $user = $event->user;

        Log::info('New notification created, broadcasting to user', [
            'notification_id' => $notification->id,
            'user_id' => $user->id,
            'notification_type' => $notification->type,
        ]);

        try {
            // Broadcast to user's private channel via Pusher/Reverb
            broadcast($notification->toBroadcast($user))->toOthers();

            Log::info('Notification broadcasted successfully', [
                'notification_id' => $notification->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast notification', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
