<?php

namespace App\Services\Chatbot\Platforms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessengerPlatform implements ChatbotPlatformInterface
{
    protected ?string $accessToken;
    protected ?string $appSecret;
    protected ?string $verifyToken;
    protected string $apiUrl = 'https://graph.facebook.com/v18.0/me/messages';

    public function __construct()
    {
        $this->accessToken = config('chatbot.platforms.messenger.access_token');
        $this->appSecret = config('chatbot.platforms.messenger.app_secret');
        $this->verifyToken = config('chatbot.platforms.messenger.verify_token');
    }

    /**
     * Send a text message to the user.
     */
    public function sendMessage(string $recipientId, string $message): array
    {
        if (!$this->accessToken) {
            Log::warning('Messenger access token not configured');
            return ['success' => false, 'error' => 'Messenger not configured'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'recipient' => [
                    'id' => $recipientId,
                ],
                'message' => [
                    'text' => $message,
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                return [
                    'success' => true,
                    'message_id' => $responseData['message_id'] ?? null,
                    'platform_message_id' => $responseData['message_id'] ?? null,
                ];
            }

            Log::error('Messenger message send failed: ' . $response->body());
            return [
                'success' => false,
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Messenger message send error: ' . $e->getMessage());
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
        if (!$this->accessToken) {
            Log::warning('Messenger access token not configured');
            return ['success' => false, 'error' => 'Messenger not configured'];
        }

        $quickReplies = [];
        foreach ($buttons as $index => $button) {
            $quickReplies[] = [
                'content_type' => 'text',
                'title' => substr($button['label'], 0, 20), // Messenger limit: 20 chars
                'payload' => $button['payload'] ?? 'QUICK_REPLY_' . $index,
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'recipient' => [
                    'id' => $recipientId,
                ],
                'message' => [
                    'text' => $message,
                    'quick_replies' => $quickReplies,
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                return [
                    'success' => true,
                    'message_id' => $responseData['message_id'] ?? null,
                    'platform_message_id' => $responseData['message_id'] ?? null,
                ];
            }

            Log::error('Messenger quick reply send failed: ' . $response->body());
            return [
                'success' => false,
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Messenger quick reply send error: ' . $e->getMessage());
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
        if (!$this->accessToken) {
            Log::warning('Messenger access token not configured');
            return ['success' => false, 'error' => 'Messenger not configured'];
        }

        // Build generic template with list
        $elements = [];
        foreach ($items as $index => $item) {
            $element = [
                'title' => $item['title'],
            ];

            if (!empty($item['subtitle'])) {
                $element['subtitle'] = $item['subtitle'];
            }

            if (!empty($item['payload'])) {
                $element['default_action'] = [
                    'type' => 'postback',
                    'payload' => $item['payload'],
                ];
            }

            $elements[] = $element;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'recipient' => [
                    'id' => $recipientId,
                ],
                'message' => [
                    'attachment' => [
                        'type' => 'template',
                        'payload' => [
                            'template_type' => 'generic',
                            'elements' => $elements,
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                return [
                    'success' => true,
                    'message_id' => $responseData['message_id'] ?? null,
                    'platform_message_id' => $responseData['message_id'] ?? null,
                ];
            }

            Log::error('Messenger list send failed: ' . $response->body());
            return [
                'success' => false,
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Messenger list send error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Extract the sender ID from the webhook payload.
     */
    public function extractSenderId(array $payload): ?string
    {
        return $payload['sender']['id'] ?? null;
    }

    /**
     * Extract the message text from the webhook payload.
     */
    public function extractMessage(array $payload): ?string
    {
        return $payload['message']['text'] ?? null;
    }

    /**
     * Extract quick reply payload if present.
     */
    public function extractQuickReplyPayload(array $payload): ?string
    {
        return $payload['message']['quick_reply']['payload'] ?? null;
    }

    /**
     * Check if the webhook payload is a message event.
     */
    public function isMessageEvent(array $payload): bool
    {
        return isset($payload['message']['text']) || isset($payload['postback']['payload']);
    }

    /**
     * Get the platform name.
     */
    public function getPlatformName(): string
    {
        return 'messenger';
    }

    /**
     * Handle webhook verification.
     */
    public function verifyWebhook(array $query): ?string
    {
        $mode = $query['hub_mode'] ?? null;
        $token = $query['hub_verify_token'] ?? null;
        $challenge = $query['hub_challenge'] ?? null;

        if ($mode === 'subscribe' && $token === $this->verifyToken) {
            Log::info('Messenger webhook verified successfully');
            return $challenge;
        }

        Log::warning('Messenger webhook verification failed', [
            'mode' => $mode,
            'token_match' => $token === $this->verifyToken,
        ]);

        return null;
    }

    /**
     * Format a phone number for the platform.
     * Messenger uses PSID (Page-Scoped ID), not phone numbers.
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Messenger doesn't use phone numbers, return as-is
        return $phone;
    }

    /**
     * Get user profile information (name, profile pic).
     */
    public function getUserProfile(string $psid): ?array
    {
        if (!$this->accessToken) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get('https://graph.facebook.com/v18.0/' . $psid, [
                'fields' => 'first_name,last_name,profile_pic',
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Messenger get user profile error: ' . $e->getMessage());
        }

        return null;
    }
}
