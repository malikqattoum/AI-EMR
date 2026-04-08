<?php

namespace App\Services\Chatbot\Actions;

use App\Models\Appointment;
use App\Models\ChatbotConversation;
use App\Models\User;
use App\Services\Chatbot\ChatbotActionHandler;
use App\Services\Chatbot\Platforms\ChatbotPlatformInterface;
use Illuminate\Support\Facades\DB;

class CancelAppointmentAction extends ChatbotActionHandler
{
    /**
     * Handle the cancel appointment action.
     */
    public function handle(ChatbotConversation $conversation, string $message, ChatbotPlatformInterface $platform, array $context = []): array
    {
        $state = $conversation->state;
        $patient = $conversation->patient;

        // If patient not identified, ask for phone number
        if (!$patient) {
            return $this->askForPatientIdentification($conversation, $message);
        }

        // State machine for cancellation flow
        if ($state === 'idle') {
            return $this->showAppointmentsToCancel($conversation, $patient);
        }

        if ($state === 'cancel_select_appointment') {
            return $this->processAppointmentSelection($conversation, $message, $patient);
        }

        if ($state === 'cancel_confirm') {
            return $this->confirmCancellation($conversation, $message, $patient);
        }

        // Default: show appointments to cancel
        return $this->showAppointmentsToCancel($conversation, $patient);
    }

    /**
     * Ask patient to identify themselves.
     */
    protected function askForPatientIdentification(ChatbotConversation $conversation, string $message): array
    {
        $patient = $this->tryIdentifyPatient($conversation);
        
        if ($patient) {
            return $this->showAppointmentsToCancel($conversation, $patient);
        }

        return [
            'message' => "To cancel an appointment, I need to identify your account first.\n\nPlease provide your phone number or email address:",
            'state' => 'awaiting_patient_identification',
        ];
    }

    /**
     * Show appointments that can be cancelled.
     */
    protected function showAppointmentsToCancel(ChatbotConversation $conversation, User $patient): array
    {
        $appointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>', now())
            ->orderBy('appointment_date', 'asc')
            ->limit(5)
            ->get();

        if ($appointments->isEmpty()) {
            return [
                'message' => "You have no upcoming appointments to cancel.\n\nWhat would you like to do instead?",
                'state' => 'idle',
            ];
        }

        $message = "❌ Select an appointment to cancel:\n\n";
        $buttons = [];

        foreach ($appointments as $index => $appointment) {
            $doctorName = $appointment->doctor?->user->name ?? 'Unknown';
            $date = $this->formatDate($appointment->appointment_date);
            $time = $this->formatTime($appointment->appointment_date);

            $message .= ($index + 1) . ". Dr. {$doctorName}\n";
            $message .= "   📅 {$date} at {$time}\n\n";

            $buttons[] = [
                'label' => 'Cancel #' . ($index + 1),
                'payload' => "CANCEL_APPT_{$appointment->id}",
            ];
        }

        $message .= "Reply with the appointment number or select an option below:";

        $conversation->updateState('cancel_confirm', [
            'cancellable_appointments' => $appointments->toArray(),
        ]);

        return [
            'message' => $message,
            'state' => 'cancel_confirm',
            'quick_replies' => $buttons,
        ];
    }

    /**
     * Process appointment selection.
     */
    protected function processAppointmentSelection(ChatbotConversation $conversation, string $message, User $patient): array
    {
        $context = $conversation->context ?? [];
        $appointments = $context['cancellable_appointments'] ?? [];

        $appointmentId = null;

        // Check if it's a number
        if ($this->isNumber(trim($message))) {
            $index = intval(trim($message)) - 1;
            if (isset($appointments[$index])) {
                $appointmentId = $appointments[$index]['id'];
            }
        }

        // Check if it's a quick reply payload
        if (str_starts_with($message, 'CANCEL_APPT_')) {
            $appointmentId = str_replace('CANCEL_APPT_', '', $message);
        }

        if (!$appointmentId) {
            return [
                'message' => "I couldn't identify that appointment. Please select from the list above:",
                'state' => 'cancel_select_appointment',
            ];
        }

        $appointment = Appointment::with('doctor.user')->find($appointmentId);

        if (!$appointment || $appointment->patient_id !== $patient->id) {
            return [
                'message' => "Appointment not found. Please try again:",
                'state' => 'cancel_select_appointment',
            ];
        }

        if (!$appointment->canBeCancelled()) {
            return [
                'message' => "This appointment cannot be cancelled. Please select another appointment:",
                'state' => 'cancel_select_appointment',
            ];
        }

        // Confirm cancellation
        $conversation->updateState('cancel_confirm', [
            'appointment_to_cancel_id' => $appointmentId,
        ]);

        return [
            'message' => "⚠️ Are you sure you want to cancel this appointment?\n\n" .
                "Dr. {$appointment->doctor->user->name}\n" .
                "📅 {$this->formatDate($appointment->appointment_date)} at {$this->formatTime($appointment->appointment_date)}\n\n" .
                "Reply 'yes' or 'confirm' to cancel, or 'no' to keep it:",
            'state' => 'cancel_confirm',
        ];
    }

    /**
     * Confirm and process cancellation.
     */
    protected function confirmCancellation(ChatbotConversation $conversation, string $message, User $patient): array
    {
        $context = $conversation->context ?? [];
        $appointmentId = $context['appointment_to_cancel_id'] ?? null;

        if (!$appointmentId) {
            return $this->showAppointmentsToCancel($conversation, $patient);
        }

        // Check if user confirmed
        if (!in_array(strtolower(trim($message)), ['yes', 'confirm', 'cancel', 'ok'])) {
            return [
                'message' => "Cancellation cancelled. What would you like to do instead?",
                'state' => 'idle',
            ];
        }

        try {
            DB::beginTransaction();

            $appointment = Appointment::where('id', $appointmentId)
                ->where('patient_id', $patient->id)
                ->lockForUpdate()
                ->first();

            if (!$appointment) {
                DB::rollBack();
                return [
                    'message' => "Appointment not found. Please try again.",
                    'state' => 'idle',
                ];
            }

            if (!$appointment->canBeCancelled()) {
                DB::rollBack();
                return [
                    'message' => "This appointment cannot be cancelled.",
                    'state' => 'idle',
                ];
            }

            $appointment->cancel('patient', 'Cancelled via chatbot');

            DB::commit();

            // Reset conversation
            $conversation->reset();

            return [
                'message' => "✅ Your appointment has been cancelled successfully.\n\n" .
                    "Appointment Number: {$appointment->appointment_number}\n\n" .
                    "Is there anything else I can help you with?",
                'state' => 'idle',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Chatbot appointment cancellation failed: ' . $e->getMessage(), [
                'appointment_id' => $appointmentId,
                'patient_id' => $patient->id,
            ]);

            return [
                'message' => "Sorry, there was an error cancelling your appointment. Please try again or contact support.",
                'state' => 'idle',
            ];
        }
    }

    /**
     * Get the intent name.
     */
    public function getIntentName(): string
    {
        return 'cancel_appointment';
    }
}
