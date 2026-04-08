<?php

namespace App\Services\Chatbot\Actions;

use App\Models\Appointment;
use App\Models\ChatbotConversation;
use App\Models\User;
use App\Services\Chatbot\ChatbotActionHandler;
use App\Services\Chatbot\Platforms\ChatbotPlatformInterface;

class ViewAppointmentsAction extends ChatbotActionHandler
{
    /**
     * Handle the view appointments action.
     */
    public function handle(ChatbotConversation $conversation, string $message, ChatbotPlatformInterface $platform, array $context = []): array
    {
        $patient = $conversation->patient;

        // If patient not identified, ask for phone number
        if (!$patient) {
            return $this->askForPatientIdentification($conversation, $message);
        }

        return $this->showAppointments($conversation, $patient);
    }

    /**
     * Ask patient to identify themselves.
     */
    protected function askForPatientIdentification(ChatbotConversation $conversation, string $message): array
    {
        $patient = $this->tryIdentifyPatient($conversation);
        
        if ($patient) {
            return $this->showAppointments($conversation, $patient);
        }

        return [
            'message' => "To view your appointments, I need to identify your account first.\n\nPlease provide your phone number or email address associated with your account:",
            'state' => 'awaiting_patient_identification',
        ];
    }

    /**
     * Show patient's appointments.
     */
    protected function showAppointments(ChatbotConversation $conversation, User $patient): array
    {
        $appointments = Appointment::with(['doctor.user', 'doctor.specialty'])
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date', 'asc')
            ->limit(5)
            ->get();

        if ($appointments->isEmpty()) {
            return [
                'message' => "You have no upcoming appointments.\n\nWould you like to book a new appointment?",
                'state' => 'idle',
                'quick_replies' => [
                    ['label' => 'Book Appointment', 'payload' => 'BOOK_APPOINTMENT'],
                    ['label' => 'Main Menu', 'payload' => 'MAIN_MENU'],
                ],
            ];
        }

        $message = "📋 Your Upcoming Appointments:\n\n";
        $buttons = [];

        foreach ($appointments as $index => $appointment) {
            $doctorName = $appointment->doctor?->user->name ?? 'Unknown';
            $status = ucfirst($appointment->status);
            $date = $this->formatDate($appointment->appointment_date);
            $time = $this->formatTime($appointment->appointment_date);
            $type = str_replace('_', ' ', $appointment->appointment_type);

            $message .= ($index + 1) . ". *{$doctorName}*\n";
            $message .= "   📅 {$date}\n";
            $message .= "   ⏰ {$time}\n";
            $message .= "   📌 Status: {$status}\n";
            $message .= "   💼 Type: {$type}\n\n";

            $buttons[] = [
                'label' => 'Cancel #' . ($index + 1),
                'payload' => "CANCEL_{$appointment->id}",
            ];
        }

        $message .= "Reply with a number to cancel that appointment, or select an option below:";

        $conversation->updateState('idle', [
            'appointments' => $appointments->toArray(),
        ]);

        return [
            'message' => $message,
            'state' => 'idle',
            'quick_replies' => array_merge($buttons, [
                ['label' => 'Main Menu', 'payload' => 'MAIN_MENU'],
            ]),
        ];
    }

    /**
     * Get the intent name.
     */
    public function getIntentName(): string
    {
        return 'view_appointments';
    }
}
