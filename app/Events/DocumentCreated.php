<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $document;
    public $userId;
    public $metadata;

    /**
     * Create a new event instance.
     */
    public function __construct($document, $userId = null, array $metadata = [])
    {
        $this->document = $document;
        $this->userId = $userId;
        $this->metadata = $metadata;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];
        
        if ($this->userId) {
            $channels[] = new PrivateChannel("App.User.{$this->userId}");
        }
        
        if ($this->document->hospital_id ?? null) {
            $channels[] = new PrivateChannel("hospital.{$this->document->hospital_id}");
        }
        
        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'document.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'document_id' => $this->document->id,
            'document_type' => $this->document->document_type ?? 'unknown',
            'user_id' => $this->userId,
            'created_at' => now()->toISOString(),
        ];
    }
}
