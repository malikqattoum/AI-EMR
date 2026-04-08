<?php

namespace App\Services\Chatbot\Actions;

use App\Models\ChatbotConversation;
use App\Models\Doctor;
use App\Services\Chatbot\ChatbotActionHandler;
use App\Services\Chatbot\Platforms\ChatbotPlatformInterface;

class CheckAvailabilityAction extends ChatbotActionHandler
{
    /**
     * Handle the check availability action.
     */
    public function handle(ChatbotConversation $conversation, string $message, ChatbotPlatformInterface $platform, array $context = []): array
    {
        $state = $conversation->state;

        // If in idle state, ask for doctor
        if ($state === 'idle') {
            return $this->askForDoctor($conversation);
        }

        // If awaiting doctor, process doctor selection
        if ($state === 'awaiting_doctor') {
            return $this->processDoctorSelection($conversation, $message);
        }

        // If awaiting date, check date input
        if ($state === 'awaiting_date') {
            return $this->processDateInput($conversation, $message);
        }

        // Default: start the flow
        return $this->askForDoctor($conversation);
    }

    /**
     * Ask user to select a doctor.
     */
    protected function askForDoctor(ChatbotConversation $conversation): array
    {
        $doctors = Doctor::with('user')
            ->where('is_active', true)
            ->limit(10)
            ->get();

        if ($doctors->isEmpty()) {
            return [
                'message' => "Sorry, no doctors are currently available. Please try again later.",
                'state' => 'idle',
            ];
        }

        // Update conversation state
        $conversation->updateState('awaiting_doctor', ['doctors' => $doctors->toArray()]);

        // Build list of doctors
        $doctorList = [];
        $buttons = [];
        foreach ($doctors as $index => $doctor) {
            $specialty = $doctor->specialty?->name ?? 'General';
            $doctorList[] = "Dr. {$doctor->user->name} - {$specialty}";
            $buttons[] = [
                'label' => 'Dr. ' . $doctor->user->name,
                'payload' => "DOCTOR_{$doctor->id}",
            ];
        }

        $message = $this->formatList("👨‍⚕️ Available Doctors:", $doctorList);
        $message .= "\n\nPlease reply with the doctor's number or name:";

        return [
            'message' => $message,
            'state' => 'awaiting_doctor',
            'quick_replies' => $buttons,
        ];
    }

    /**
     * Process doctor selection.
     */
    protected function processDoctorSelection(ChatbotConversation $conversation, string $message): array
    {
        $context = $conversation->context ?? [];
        $doctors = $context['doctors'] ?? [];

        // Try to parse doctor selection
        $doctorId = null;

        // Check if it's a number
        if ($this->isNumber(trim($message))) {
            $index = intval(trim($message)) - 1;
            if (isset($doctors[$index])) {
                $doctorId = $doctors[$index]['id'];
            }
        }

        // Check if it's a quick reply payload
        if (str_starts_with($message, 'DOCTOR_')) {
            $doctorId = str_replace('DOCTOR_', '', $message);
        }

        // Check if it's a doctor name
        if (!$doctorId) {
            foreach ($doctors as $doctor) {
                if (stripos($doctor['user']['name'], $message) !== false) {
                    $doctorId = $doctor['id'];
                    break;
                }
            }
        }

        if (!$doctorId) {
            return [
                'message' => "I couldn't identify that doctor. Please select from the list above:",
                'state' => 'awaiting_doctor',
            ];
        }

        $doctor = Doctor::with('user')->find($doctorId);
        if (!$doctor) {
            return [
                'message' => "Doctor not found. Please try again:",
                'state' => 'awaiting_doctor',
            ];
        }

        // Ask for date
        $conversation->updateState('awaiting_date', [
            'selected_doctor_id' => $doctorId,
            'doctor_name' => $doctor->user->name,
        ]);

        return [
            'message' => "📅 Great! Now please enter the date you'd like to check for Dr. {$doctor->user->name}:\n\n(e.g., 'tomorrow', 'Monday', '2026-04-15')",
            'state' => 'awaiting_date',
        ];
    }

    /**
     * Process date input.
     */
    protected function processDateInput(ChatbotConversation $conversation, string $message): array
    {
        $context = $conversation->context ?? [];
        $doctorId = $context['selected_doctor_id'] ?? null;

        if (!$doctorId) {
            return $this->askForDoctor($conversation);
        }

        $date = $this->parseDate($message);
        if (!$date) {
            return [
                'message' => "I couldn't understand that date. Please enter a valid date (e.g., 'tomorrow', 'Monday', '2026-04-15'):",
                'state' => 'awaiting_date',
            ];
        }

        $doctor = Doctor::find($doctorId);
        if (!$doctor) {
            return [
                'message' => "Doctor not found. Let's start over.\n\nWhat would you like to do?",
                'state' => 'idle',
            ];
        }

        // Get available slots
        $slots = $doctor->getAvailableSlots($date->format('Y-m-d'));

        if ($slots->isEmpty()) {
            return [
                'message' => "Dr. {$doctor->user->name} has no available slots on {$this->formatDate($date)}.\n\nWould you like to:\n1. Check another date\n2. See available doctors\n3. Return to main menu",
                'state' => 'awaiting_date',
                'context' => ['selected_doctor_id' => $doctorId],
            ];
        }

        // Format available slots
        $slotList = [];
        $buttons = [];
        $slotsTaken = 0;
        foreach ($slots as $slot) {
            if ($slotsTaken >= 5) break; // Show max 5 slots
            $slotTime = \Carbon\Carbon::parse($slot['datetime']);
            $slotList[] = $this->formatTime($slotTime);
            $buttons[] = [
                'label' => $this->formatTime($slotTime),
                'payload' => "TIME_{$slot['datetime']}",
            ];
            $slotsTaken++;
        }

        $message = $this->formatList(
            "✅ Available times for Dr. {$doctor->user->name} on {$this->formatDate($date)}:",
            $slotList
        );

        if ($slots->count() > 5) {
            $message .= "\n\n... and " . ($slots->count() - 5) . " more slots available.";
        }

        $message .= "\n\nSelect a time or enter 'book' to book one of these times:";

        $conversation->updateState('awaiting_time', [
            'selected_doctor_id' => $doctorId,
            'selected_date' => $date->format('Y-m-d'),
            'available_slots' => $slots->take(5)->values()->toArray(),
        ]);

        return [
            'message' => $message,
            'state' => 'awaiting_time',
            'quick_replies' => $buttons,
        ];
    }

    /**
     * Get the intent name.
     */
    public function getIntentName(): string
    {
        return 'check_availability';
    }
}
