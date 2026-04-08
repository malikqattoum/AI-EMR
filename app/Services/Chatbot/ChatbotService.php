<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Models\ChatbotIntent;
use App\Models\User;
use App\Services\Chatbot\Platforms\ChatbotPlatformInterface;
use App\Services\Chatbot\Platforms\WhatsAppPlatform;
use App\Services\Chatbot\Platforms\MessengerPlatform;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class ChatbotService
{
    /**
     * Process an incoming message from a platform.
     */
    public function processMessage(string $platform, string $platformUserId, string $message, ?string $quickReplyPayload = null): array
    {
        try {
            // Get or create conversation
            $conversation = $this->getOrCreateConversation($platform, $platformUserId);

            // Log inbound message
            $conversation->addMessage('inbound', $message, [
                'payload' => $quickReplyPayload ? ['quick_reply' => $quickReplyPayload] : null,
            ]);

            // Determine intent
            $intent = $this->determineIntent($conversation, $message, $quickReplyPayload);

            // Execute action
            $response = $this->executeAction($conversation, $message, $intent, $platform);

            // Send response(s)
            $sendResult = $this->sendResponse($conversation, $response, $platform);

            // Log outbound message
            $conversation->addMessage('outbound', $response['message'] ?? 'No response', [
                'status' => $sendResult['success'] ? 'sent' : 'failed',
                'platform_message_id' => $sendResult['platform_message_id'] ?? null,
                'error_message' => $sendResult['error'] ?? null,
            ]);

            return $sendResult;
        } catch (\Exception $e) {
            Log::error('Chatbot message processing failed: ' . $e->getMessage(), [
                'platform' => $platform,
                'platform_user_id' => $platformUserId,
                'message' => $message,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => 'An error occurred while processing your message.',
            ];
        }
    }

    /**
     * Get or create a conversation for a platform user.
     */
    protected function getOrCreateConversation(string $platform, string $platformUserId): ChatbotConversation
    {
        // Try to find patient by platform user ID
        $patient = $this->findPatientByPlatformId($platform, $platformUserId);

        return ChatbotConversation::getOrCreateActive(
            $platform,
            $platformUserId,
            $patient?->id
        );
    }

    /**
     * Find patient by platform ID.
     */
    protected function findPatientByPlatformId(string $platform, string $platformUserId): ?User
    {
        if ($platform === 'whatsapp') {
            $phone = preg_replace('/[^0-9+]/', '', $platformUserId);
            return User::where('phone', $phone)
                ->where('role', 'patient')
                ->first();
        }

        // For Messenger, we might need to link PSID to patient manually
        // or ask the user to identify themselves
        return null;
    }

    /**
     * Determine the user's intent.
     */
    protected function determineIntent(ChatbotConversation $conversation, string $message, ?string $quickReplyPayload = null): ?ChatbotIntent
    {
        // Check quick reply payload first
        if ($quickReplyPayload) {
            $intent = $this->matchQuickReplyPayload($quickReplyPayload);
            if ($intent) {
                return $intent;
            }
        }

        // Check for state-based transitions
        $stateIntent = $this->matchStateTransition($conversation, $message);
        if ($stateIntent) {
            return $stateIntent;
        }

        // Use AI for intent recognition
        $intent = $this->recognizeIntentWithAI($conversation, $message);
        if ($intent) {
            return $intent;
        }

        // Fallback to keyword matching
        return $this->matchKeywords($message);
    }

    /**
     * Match quick reply payload to intent.
     */
    protected function matchQuickReplyPayload(string $payload): ?ChatbotIntent
    {
        $intentMap = [
            'MAIN_MENU' => 'help',
            'BOOK_APPOINTMENT' => 'book_appointment',
            'CHECK_AVAILABILITY' => 'check_availability',
            'VIEW_APPOINTMENTS' => 'view_appointments',
            'CANCEL_APPOINTMENT' => 'cancel_appointment',
            'RESCHEDULE_APPOINTMENT' => 'reschedule_appointment',
        ];

        foreach ($intentMap as $payloadKey => $intentName) {
            if (str_starts_with($payload, $payloadKey)) {
                return ChatbotIntent::where('name', $intentName)->first();
            }
        }

        return null;
    }

    /**
     * Match state-based transitions.
     */
    protected function matchStateTransition(ChatbotConversation $conversation, string $message): ?ChatbotIntent
    {
        $state = $conversation->state;
        $context = $conversation->context ?? [];

        // If awaiting patient identification, try to identify patient
        if ($state === 'awaiting_patient_identification') {
            return $this->identifyPatient($conversation, $message);
        }

        // If in a booking/check_availability flow, continue with that intent
        if (str_starts_with($state, 'booking_') || $state === 'awaiting_doctor' || $state === 'awaiting_date' || $state === 'awaiting_time') {
            if (str_starts_with($state, 'booking')) {
                return ChatbotIntent::where('name', 'book_appointment')->first();
            }
            if ($state === 'awaiting_doctor' || $state === 'awaiting_date' || $state === 'awaiting_time') {
                return ChatbotIntent::where('name', 'check_availability')->first();
            }
        }

        // If in cancellation flow
        if (str_starts_with($state, 'cancel_')) {
            return ChatbotIntent::where('name', 'cancel_appointment')->first();
        }

        // If in reschedule flow
        if (str_starts_with($state, 'reschedule_')) {
            return ChatbotIntent::where('name', 'reschedule_appointment')->first();
        }

        return null;
    }

    /**
     * Identify patient from phone or email.
     */
    protected function identifyPatient(ChatbotConversation $conversation, string $message): ?ChatbotIntent
    {
        $message = trim($message);

        // Try as phone number
        $phone = preg_replace('/[^0-9+]/', '', $message);
        if (strlen($phone) >= 10) {
            $patient = User::where('phone', $phone)->where('role', 'patient')->first();
            if ($patient) {
                $conversation->update([
                    'patient_id' => $patient->id,
                ]);
                $conversation->patient = $patient;

                // Return the original intent based on context
                return $this->matchKeywords($conversation->context['original_intent'] ?? 'help');
            }
        }

        // Try as email
        if (filter_var($message, FILTER_VALIDATE_EMAIL)) {
            $patient = User::where('email', $message)->where('role', 'patient')->first();
            if ($patient) {
                $conversation->update([
                    'patient_id' => $patient->id,
                ]);
                $conversation->patient = $patient;

                return $this->matchKeywords($conversation->context['original_intent'] ?? 'help');
            }
        }

        // Return help intent with error message
        return null;
    }

    /**
     * Use AI to recognize intent.
     */
    protected function recognizeIntentWithAI(ChatbotConversation $conversation, string $message): ?ChatbotIntent
    {
        if (!config('chatbot.ai_enabled') || !env('OPENAI_API_KEY')) {
            return null;
        }

        try {
            // Get all enabled intents
            $intents = ChatbotIntent::enabled()
                ->orderedByPriority()
                ->get();

            if ($intents->isEmpty()) {
                return null;
            }

            // Build training examples for AI
            $intentExamples = $intents->map(function ($intent) {
                $examples = implode("\n", $intent->training_phrases ?? []);
                return "Intent: {$intent->name}\nExamples:\n{$examples}";
            })->implode("\n\n");

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are an intent classification system for a medical chatbot. Given the user's message, classify it into one of the following intents. Respond with ONLY the intent name, nothing else.\n\nAvailable Intents:\n{$intentExamples}\n\nIf the message doesn't match any intent, respond with 'unknown'.",
                    ],
                    [
                        'role' => 'user',
                        'content' => $message,
                    ],
                ],
                'temperature' => 0.1,
                'max_tokens' => 50,
            ]);

            $predictedIntent = trim($response->choices[0]->message->content);

            if ($predictedIntent && $predictedIntent !== 'unknown') {
                return ChatbotIntent::where('name', $predictedIntent)->first();
            }
        } catch (\Exception $e) {
            Log::warning('AI intent recognition failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Match keywords to intents as fallback.
     */
    protected function matchKeywords(string $message): ?ChatbotIntent
    {
        $message = strtolower(trim($message));

        $keywordMap = [
            ['greeting', ['hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening', 'start']],
            ['help', ['help', 'what can you do', 'options', 'menu', 'main menu', 'show menu']],
            ['goodbye', ['bye', 'goodbye', 'thank you', 'thanks', 'that is all', 'done', 'exit', 'quit']],
            ['check_availability', ['check availability', 'available slots', 'when is doctor available', 'free slots', 'open appointments', 'available times', 'schedule']],
            ['book_appointment', ['book appointment', 'make appointment', 'schedule appointment', 'book a visit', 'i want to see a doctor', 'need appointment', 'book']],
            ['view_appointments', ['my appointments', 'view appointments', 'see appointments', 'upcoming appointments', 'appointments']],
            ['cancel_appointment', ['cancel appointment', 'cancel my appointment', 'delete appointment', 'remove appointment', 'cancel']],
            ['reschedule_appointment', ['reschedule appointment', 'change appointment', 'reschedule my appointment', 'move appointment', 'reschedule']],
        ];

        foreach ($keywordMap as [$intentName, $keywords]) {
            foreach ($keywords as $keyword) {
                // Use word boundary matching to avoid false positives (e.g., "Facebook" matching "book")
                $pattern = '/\b' . preg_quote($keyword, '/') . '\b/i';
                if (preg_match($pattern, $message)) {
                    $intent = ChatbotIntent::where('name', $intentName)->first();
                    if ($intent && $intent->enabled) {
                        return $intent;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Execute the action for an intent.
     */
    protected function executeAction(ChatbotConversation $conversation, string $message, ?ChatbotIntent $intent, string $platform): array
    {
        if (!$intent) {
            return [
                'message' => "I'm sorry, I didn't understand that. Here's what I can help you with:\n\n1. Check Doctor Availability\n2. Book an Appointment\n3. View My Appointments\n4. Cancel an Appointment\n5. Reschedule an Appointment\n\nPlease reply with a number or tell me what you need.",
                'state' => 'idle',
            ];
        }

        // If intent has an action handler, execute it
        if ($intent->action_handler) {
            try {
                $handler = new $intent->action_handler();
                if ($handler instanceof ChatbotActionHandler) {
                    $platformAdapter = $this->getPlatformAdapter($platform);
                    return $handler->handle($conversation, $message, $platformAdapter);
                }
            } catch (\Exception $e) {
                Log::error('Chatbot action handler failed: ' . $e->getMessage(), [
                    'intent' => $intent->name,
                    'handler' => $intent->action_handler,
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        // Return default response from intent
        $response = $intent->getRandomResponse();
        if ($response) {
            return [
                'message' => $response,
                'state' => 'idle',
            ];
        }

        // Fallback response
        return [
            'message' => "I'm here to help! What would you like to do?",
            'state' => 'idle',
        ];
    }

    /**
     * Send response to user via platform.
     */
    protected function sendResponse(ChatbotConversation $conversation, array $response, string $platform): array
    {
        $platformAdapter = $this->getPlatformAdapter($platform);
        $recipientId = $conversation->platform_user_id;

        // Update conversation state
        if (isset($response['state'])) {
            $conversation->updateState($response['state'], $response['context'] ?? []);
        }

        // Send with quick replies if available
        if (!empty($response['quick_replies'])) {
            return $platformAdapter->sendQuickReply(
                $recipientId,
                $response['message'],
                $response['quick_replies']
            );
        }

        // Send regular message
        return $platformAdapter->sendMessage($recipientId, $response['message']);
    }

    /**
     * Get platform adapter instance.
     */
    protected function getPlatformAdapter(string $platform): ChatbotPlatformInterface
    {
        return match($platform) {
            'whatsapp' => new WhatsAppPlatform(),
            'messenger' => new MessengerPlatform(),
            default => throw new \InvalidArgumentException("Unsupported platform: {$platform}"),
        };
    }

    /**
     * Send a proactive message to a user.
     */
    public function sendProactiveMessage(string $platform, string $platformUserId, string $message): array
    {
        try {
            $platformAdapter = $this->getPlatformAdapter($platform);
            return $platformAdapter->sendMessage($platformUserId, $message);
        } catch (\Exception $e) {
            Log::error('Chatbot proactive message failed: ' . $e->getMessage(), [
                'platform' => $platform,
                'platform_user_id' => $platformUserId,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
