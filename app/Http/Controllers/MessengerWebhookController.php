<?php

namespace App\Http\Controllers;

use App\Services\Chatbot\ChatbotService;
use App\Services\Chatbot\Platforms\MessengerPlatform;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MessengerWebhookController extends Controller
{
    protected MessengerPlatform $platform;
    protected ChatbotService $chatbotService;

    public function __construct()
    {
        $this->platform = new MessengerPlatform();
        $this->chatbotService = app(ChatbotService::class);
    }

    /**
     * Handle webhook verification (GET request).
     */
    public function verify(Request $request)
    {
        $challenge = $this->platform->verifyWebhook($request->query());

        if ($challenge) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        Log::warning('Messenger webhook verification failed', [
            'query' => $request->query(),
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Handle incoming webhook events (POST request).
     */
    public function webhook(Request $request)
    {
        try {
            $payload = $request->all();

            // Log the webhook for debugging
            Log::info('Messenger webhook received', ['payload' => $payload]);

            // Handle messaging events
            if (isset($payload['entry'])) {
                foreach ($payload['entry'] as $entry) {
                    foreach ($entry['messaging'] ?? [] as $messaging) {
                        $this->processMessagingEvent($messaging);
                    }
                }
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Messenger webhook processing error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response('Error', 500);
        }
    }

    /**
     * Process a messaging event.
     */
    protected function processMessagingEvent(array $messaging): void
    {
        // Skip if no message
        if (!isset($messaging['sender']['id'])) {
            return;
        }

        $senderId = $messaging['sender']['id'];

        // Handle message
        if (isset($messaging['message'])) {
            $message = $this->platform->extractMessage($messaging);
            $quickReplyPayload = $this->platform->extractQuickReplyPayload($messaging);

            if ($message) {
                $this->handleMessage($senderId, $message, $quickReplyPayload);
            }
        }

        // Handle postback (button clicks)
        if (isset($messaging['postback']['payload'])) {
            $payload = $messaging['postback']['payload'];
            $this->handlePostback($senderId, $payload);
        }
    }

    /**
     * Handle incoming message.
     */
    protected function handleMessage(string $senderId, string $message, ?string $quickReplyPayload = null): void
    {
        Log::info('Messenger message received', [
            'sender_id' => $senderId,
            'message' => $message,
            'quick_reply_payload' => $quickReplyPayload,
        ]);

        // Process through chatbot service
        $this->chatbotService->processMessage(
            'messenger',
            $senderId,
            $message,
            $quickReplyPayload
        );
    }

    /**
     * Handle postback (button click).
     */
    protected function handlePostback(string $senderId, string $payload): void
    {
        Log::info('Messenger postback received', [
            'sender_id' => $senderId,
            'payload' => $payload,
        ]);

        // Treat postback as a message with quick reply payload
        $this->chatbotService->processMessage(
            'messenger',
            $senderId,
            $payload,
            $payload
        );
    }

    /**
     * Handle message delivery receipts.
     */
    protected function handleDeliveryReceipt(array $receipt): void
    {
        $messageId = $receipt['mid'] ?? null;
        $watermark = $receipt['watermark'] ?? null;

        if ($messageId) {
            Log::info('Messenger message delivered', [
                'message_id' => $messageId,
                'watermark' => $watermark,
            ]);

            // Update message status in database
            // This would be implemented based on your message tracking needs
        }
    }

    /**
     * Handle message read receipts.
     */
    protected function handleReadReceipt(array $read): void
    {
        $watermark = $read['watermark'] ?? null;

        if ($watermark) {
            Log::info('Messenger message read', [
                'watermark' => $watermark,
            ]);

            // Update message status in database
        }
    }
}
