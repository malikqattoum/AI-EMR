<?php

namespace App\Services\Chatbot\Platforms;

interface ChatbotPlatformInterface
{
    /**
     * Send a text message to the user.
     */
    public function sendMessage(string $recipientId, string $message): array;

    /**
     * Send a message with quick reply buttons.
     */
    public function sendQuickReply(string $recipientId, string $message, array $buttons): array;

    /**
     * Send a structured list (for menus/options).
     */
    public function sendList(string $recipientId, string $header, array $items): array;

    /**
     * Extract the sender ID from the webhook payload.
     */
    public function extractSenderId(array $payload): ?string;

    /**
     * Extract the message text from the webhook payload.
     */
    public function extractMessage(array $payload): ?string;

    /**
     * Extract quick reply payload if present.
     */
    public function extractQuickReplyPayload(array $payload): ?string;

    /**
     * Check if the webhook payload is a message event.
     */
    public function isMessageEvent(array $payload): bool;

    /**
     * Get the platform name.
     */
    public function getPlatformName(): string;

    /**
     * Handle webhook verification (for platforms that require it).
     */
    public function verifyWebhook(array $query): ?string;

    /**
     * Format a phone number for the platform.
     */
    public function formatPhoneNumber(string $phone): string;
}
