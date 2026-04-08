<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotConversation;
use App\Services\Chatbot\Platforms\ChatbotPlatformInterface;

abstract class ChatbotActionHandler
{
    /**
     * Handle the action and return a response.
     *
     * @param ChatbotConversation $conversation The current conversation
     * @param string $message The user's message
     * @param ChatbotPlatformInterface $platform The platform adapter
     * @param array $context Additional context
     * @return array ['message' => string, 'state' => string, 'context' => array, 'quick_replies' => array]
     */
    abstract public function handle(ChatbotConversation $conversation, string $message, ChatbotPlatformInterface $platform, array $context = []): array;

    /**
     * Get the intent name for this action.
     */
    abstract public function getIntentName(): string;

    /**
     * Send a message with quick reply buttons.
     */
    protected function formatQuickReplyMessage(string $message, array $buttons): array
    {
        return [
            'message' => $message,
            'quick_replies' => $buttons,
        ];
    }

    /**
     * Format a numbered list for display.
     */
    protected function formatList(string $header, array $items): string
    {
        $output = $header . "\n\n";
        foreach ($items as $index => $item) {
            $output .= ($index + 1) . ". " . $item;
            if ($index < count($items) - 1) {
                $output .= "\n";
            }
        }
        return $output;
    }

    /**
     * Format a date for display.
     */
    protected function formatDate(\Carbon\Carbon $date): string
    {
        return $date->format('l, F j, Y');
    }

    /**
     * Format a time for display.
     */
    protected function formatTime(\Carbon\Carbon $time): string
    {
        return $time->format('g:i A');
    }

    /**
     * Parse a date string from user input.
     */
    protected function parseDate(string $input): ?\Carbon\Carbon
    {
        try {
            // Try parsing various date formats
            $date = \Carbon\Carbon::parse($input);
            
            // Validate it's a reasonable date (not too far in past or future)
            if ($date->lt(now()->subDays(1)) || $date->gt(now()->addMonths(3))) {
                return null;
            }

            return $date;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if input is a number.
     */
    protected function isNumber(string $input): bool
    {
        return is_numeric($input);
    }

    /**
     * Get patient's primary doctor or default doctor.
     */
    protected function getDoctorForPatient($patient): ?\App\Models\Doctor
    {
        if ($patient->primary_doctor_id) {
            $doctor = \App\Models\User::find($patient->primary_doctor_id);
            return $doctor?->doctor;
        }

        // Return first active doctor as fallback
        return \App\Models\Doctor::where('is_active', true)->orderBy('id')->first();
    }

    /**
     * Try to identify patient from conversation's platform user ID.
     * Returns the patient if found, updates conversation patient_id.
     */
    protected function tryIdentifyPatient(ChatbotConversation $conversation): ?\App\Models\User
    {
        // If patient already identified, return it
        if ($conversation->patient) {
            return $conversation->patient;
        }

        // Try to identify by WhatsApp phone number
        if ($conversation->platform === 'whatsapp' && $conversation->platform_user_id) {
            $phone = preg_replace('/[^0-9]/', '', $conversation->platform_user_id);
            if (strlen($phone) >= 10) {
                $patient = \App\Models\User::whereRaw("REPLACE(REPLACE(REPLACE(phone, '-', ''), ' ', ''), '+', '') = ?", [$phone])
                    ->where('role', 'patient')
                    ->first();

                if ($patient) {
                    $conversation->update(['patient_id' => $patient->id]);
                    $conversation->patient = $patient;
                    return $patient;
                }
            }
        }

        return null;
    }
}
