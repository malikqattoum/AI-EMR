<?php

namespace App\Services\Chatbot\Platforms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppPlatform implements ChatbotPlatformInterface
{
    protected ?string $accessToken;
    protected ?string $phoneNumberId;
    protected string $apiUrl = 'https://graph.facebook.com/v18.0';

    public function __construct()
    {
        $this->accessToken = config('chatbot.platforms.whatsapp.access_token');
        $this->phoneNumberId = config('chatbot.platforms.whatsapp.phone_number_id');
    }

    /**
     * Send a text message to the user.
     */
    public function sendMessage(string $recipientId, string $message): array
    {
        if (!$this->accessToken || !$this->phoneNumberId) {
            Log::warning('WhatsApp credentials not configured');
            return ['success' => false, 'error' => 'WhatsApp not configured'];
        }

        try {
            $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $this->formatPhoneNumber($recipientId),
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $message,
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                return [
                    'success' => true,
                    'message_id' => $responseData['messages'][0]['id'] ?? null,
                    'platform_message_id' => $responseData['messages'][0]['id'] ?? null,
                ];
            }

            Log::error('WhatsApp message send failed: ' . $response->body());
            return [
                'success' => false,
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp message send error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send a message with quick reply buttons.
     */
    public function sendQuickReply(string $recipientId, string $message, array $buttons): array
    {
        if (!$this->accessToken || !$this->phoneNumberId) {
            Log::warning('WhatsApp credentials not configured');
            return ['success' => false, 'error' => 'WhatsApp not configured'];
        }

        // WhatsApp uses interactive buttons
        $quickReplies = [];
        foreach ($buttons as $index => $button) {
            $quickReplies[] = [
                'type' => 'reply',
                'reply' => [
                    'id' => 'quick_reply_' . $index,
                    'title' => substr($button['label'], 0, 20), // WhatsApp limit: 20 chars
                ],
            ];
        }

        try {
            $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $this->formatPhoneNumber($recipientId),
                'type' => 'interactive',
                'interactive' => [
                    'type' => 'button',
                    'body' => [
                        'text' => $message,
                    ],
                    'action' => [
                        'buttons' => $quickReplies,
                    ],
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                return [
                    'success' => true,
                    'message_id' => $responseData['messages'][0]['id'] ?? null,
                    'platform_message_id' => $responseData['messages'][0]['id'] ?? null,
                ];
            }

            Log::error('WhatsApp quick reply send failed: ' . $response->body());
            return [
                'success' => false,
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp quick reply send error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send a structured list (for menus/options).
     */
    public function sendList(string $recipientId, string $header, array $items): array
    {
        if (!$this->accessToken || !$this->phoneNumberId) {
            Log::warning('WhatsApp credentials not configured');
            return ['success' => false, 'error' => 'WhatsApp not configured'];
        }

        // Convert list to numbered text message for WhatsApp
        $message = $header . "\n\n";
        foreach ($items as $index => $item) {
            $message .= ($index + 1) . ". " . $item['title'];
            if (!empty($item['subtitle'])) {
                $message .= " - " . $item['subtitle'];
            }
            $message .= "\n";
        }

        return $this->sendMessage($recipientId, $message);
    }

    /**
     * Extract the sender ID from the webhook payload.
     */
    public function extractSenderId(array $payload): ?string
    {
        // Meta WhatsApp Business API
        if (isset($payload['entry'])) {
            foreach ($payload['entry'] as $entry) {
                foreach ($entry['changes'] ?? [] as $change) {
                    $value = $change['value'] ?? [];
                    if (isset($value['messages']) && !empty($value['messages'])) {
                        return $value['messages'][0]['from'] ?? null;
                    }
                }
            }
        }

        // Twilio WhatsApp
        return $payload['From'] ?? null;
    }

    /**
     * Extract the message text from the webhook payload.
     */
    public function extractMessage(array $payload): ?string
    {
        // Meta WhatsApp Business API
        if (isset($payload['entry'])) {
            foreach ($payload['entry'] as $entry) {
                foreach ($entry['changes'] ?? [] as $change) {
                    $value = $change['value'] ?? [];
                    if (isset($value['messages']) && !empty($value['messages'])) {
                        $message = $value['messages'][0];
                        if (isset($message['text']['body'])) {
                            return $message['text']['body'];
                        }
                        if (isset($message['content'])) {
                            return $message['content'];
                        }
                    }
                }
            }
        }

        // Twilio WhatsApp
        return $payload['Body'] ?? null;
    }

    /**
     * Extract quick reply payload if present.
     */
    public function extractQuickReplyPayload(array $payload): ?string
    {
        // Meta WhatsApp button replies
        if (isset($payload['entry'])) {
            foreach ($payload['entry'] as $entry) {
                foreach ($entry['changes'] ?? [] as $change) {
                    $value = $change['value'] ?? [];
                    if (isset($value['messages']) && !empty($value['messages'])) {
                        $message = $value['messages'][0];
                        if (isset($message['button']['text'])) {
                            return $message['button']['text'];
                        }
                        if (isset($message['interactive']['button_reply'])) {
                            return $message['interactive']['button_reply']['id'] ?? null;
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Check if the webhook payload is a message event.
     */
    public function isMessageEvent(array $payload): bool
    {
        // Meta WhatsApp Business API
        if (isset($payload['entry'])) {
            foreach ($payload['entry'] as $entry) {
                foreach ($entry['changes'] ?? [] as $change) {
                    $value = $change['value'] ?? [];
                    if (isset($value['messages']) && !empty($value['messages'])) {
                        return true;
                    }
                }
            }
        }

        // Twilio WhatsApp
        return isset($payload['Body']) && isset($payload['From']);
    }

    /**
     * Get the platform name.
     */
    public function getPlatformName(): string
    {
        return 'whatsapp';
    }

    /**
     * Handle webhook verification (for Meta WhatsApp).
     */
    public function verifyWebhook(array $query): ?string
    {
        $mode = $query['hub_mode'] ?? null;
        $token = $query['hub_verify_token'] ?? null;
        $challenge = $query['hub_challenge'] ?? null;

        $verifyToken = config('chatbot.platforms.whatsapp.verify_token');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return $challenge;
        }

        return null;
    }

    /**
     * Format a phone number for the platform.
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // If number doesn't start with +, add it
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }
}
