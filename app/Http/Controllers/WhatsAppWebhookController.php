<?php

namespace App\Http\Controllers;

use App\Services\Chatbot\ChatbotService;
use App\Services\Chatbot\Platforms\WhatsAppPlatform;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WhatsAppWebhookController extends Controller
{
    protected WhatsAppPlatform $platform;
    protected ChatbotService $chatbotService;

    public function __construct()
    {
        $this->platform = new WhatsAppPlatform();
        $this->chatbotService = app(ChatbotService::class);
    }

    /**
     * Handle WhatsApp webhook verification (required by Meta/Twilio)
     */
    public function verify(Request $request)
    {
        // Try Meta/WhatsApp Business API verification first
        $challenge = $this->platform->verifyWebhook($request->query());
        if ($challenge) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        // Fallback to legacy verification
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $verifyToken = config('whatsapp.webhook_verify_token', env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'medcura-webhook-verify'));

        // Verify the webhook
        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info('WhatsApp webhook verified successfully');
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        Log::warning('WhatsApp webhook verification failed', [
            'mode' => $mode,
            'token_match' => $token === $verifyToken,
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Handle incoming WhatsApp webhook events
     */
    public function webhook(Request $request)
    {
        try {
            $body = $request->all();

            // Log the webhook for debugging
            Log::info('WhatsApp webhook received', ['body' => $body]);

            // Handle Twilio webhooks
            if (isset($body['MessageSid']) || isset($body['MessagingAccountSid'])) {
                return $this->handleTwilioWebhook($body);
            }

            // Handle Meta/WhatsApp Business API webhooks
            if (isset($body['entry'])) {
                return $this->handleMetaWebhook($body);
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('WhatsApp webhook processing error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response('Error', 500);
        }
    }

    /**
     * Handle Twilio WhatsApp webhooks
     */
    protected function handleTwilioWebhook(array $body): Response
    {
        $messageSid = $body['MessageSid'] ?? null;
        $messageStatus = $body['MessageStatus'] ?? null;
        $from = $body['From'] ?? null;
        $to = $body['To'] ?? null;
        $bodyContent = $body['Body'] ?? null;

        // Handle status callbacks
        if ($messageStatus) {
            return $this->handleStatusUpdate($messageSid, $messageStatus, [
                'provider' => 'twilio',
                'from' => $from,
                'to' => $to,
            ]);
        }

        // Handle incoming messages
        if ($bodyContent && $from) {
            return $this->handleIncomingMessage([
                'message_id' => $messageSid,
                'from' => $from,
                'to' => $to,
                'body' => $bodyContent,
                'provider' => 'twilio',
            ]);
        }

        return response('OK', 200);
    }

    /**
     * Handle Meta WhatsApp Business API webhooks
     */
    protected function handleMetaWebhook(array $body): Response
    {
        foreach ($body['entry'] as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                $value = $change['value'] ?? [];

                // Handle status updates
                if (isset($value['statuses'])) {
                    foreach ($value['statuses'] as $status) {
                        $this->handleStatusUpdate(
                            $status['id'],
                            $status['status'],
                            [
                                'provider' => 'meta',
                                'recipient_id' => $status['recipient_id'] ?? null,
                            ]
                        );
                    }
                }

                // Handle incoming messages
                if (isset($value['messages'])) {
                    foreach ($value['messages'] as $message) {
                        $messageData = [
                            'message_id' => $message['id'] ?? null,
                            'from' => $message['from'] ?? null,
                            'to' => $value['metadata']['phone_number_id'] ?? null,
                            'body' => $message['text']['body'] ?? $message['content'] ?? null,
                            'provider' => 'meta',
                        ];

                        // Handle button replies (interactive messages)
                        if (isset($message['interactive']['button_reply'])) {
                            $messageData['quick_reply_payload'] = $message['interactive']['button_reply']['id'] ?? null;
                        }

                        $this->handleIncomingMessage($messageData);
                    }
                }
            }
        }

        return response('OK', 200);
    }

    /**
     * Handle message status updates
     */
    protected function handleStatusUpdate(?string $messageId, string $status, array $metadata = []): Response
    {
        if (!$messageId) {
            return response('OK', 200);
        }

        Log::info('WhatsApp message status update', [
            'message_id' => $messageId,
            'status' => $status,
            'metadata' => $metadata,
        ]);

        // Map WhatsApp status to internal status
        $internalStatus = match($status) {
            'queued', 'accepted' => 'pending',
            'sent', 'delivered' => 'delivered',
            'read' => 'read',
            'failed', 'undelivered' => 'failed',
            default => $status,
        };

        // Log the status update for now
        // In production, you would update a messages table here
        DB::table('whatsapp_message_logs')->updateOrInsert(
            ['message_id' => $messageId],
            [
                'status' => $internalStatus,
                'provider' => $metadata['provider'] ?? 'unknown',
                'updated_at' => now(),
            ]
        );

        return response('OK', 200);
    }

    /**
     * Handle incoming messages from users
     */
    protected function handleIncomingMessage(array $data): Response
    {
        Log::info('WhatsApp incoming message', $data);

        // Extract message details
        $from = $data['from'] ?? null;
        $body = $data['body'] ?? null;
        $messageId = $data['message_id'] ?? null;
        $quickReplyPayload = $data['quick_reply_payload'] ?? null;

        if (!$from || !$body) {
            return response('OK', 200);
        }

        try {
            // Process through chatbot service
            $this->chatbotService->processMessage(
                'whatsapp',
                $from,
                $body,
                $quickReplyPayload
            );

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Chatbot message processing failed: ' . $e->getMessage(), [
                'from' => $from,
                'message' => $body,
            ]);

            return response('OK', 200); // Still return 200 to prevent webhook retries
        }
    }
}
