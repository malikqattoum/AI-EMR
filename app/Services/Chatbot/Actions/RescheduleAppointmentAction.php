<?php

namespace App\Services\Chatbot\Actions;

use App\Models\Appointment;
use App\Models\ChatbotConversation;
use App\Models\Doctor;
use App\Models\User;
use App\Services\Chatbot\ChatbotActionHandler;
use App\Services\Chatbot\Platforms\ChatbotPlatformInterface;
use Illuminate\Support\Facades\DB;

class RescheduleAppointmentAction extends ChatbotActionHandler
{
    /**
     * Handle the reschedule appointment action.
     */
    public function handle(ChatbotConversation $conversation, string $message, ChatbotPlatformInterface $platform, array $context = []): array
    {
        $state = $conversation->state;
        $patient = $conversation->patient;

        // If patient not identified, ask for phone number
        if (!$patient) {
            return $this->askForPatientIdentification($conversation, $message);
        }

        // State machine for reschedule flow
        if ($state === 'idle') {
            return $this->showAppointmentsToReschedule($conversation, $patient);
        }

        if ($state === 'reschedule_select_appointment') {
            return $this->processAppointmentSelection($conversation, $message, $patient);
        }

        if ($state === 'reschedule_select_date') {
            return $this->processDateSelection($conversation, $message, $patient);
        }

        if ($state === 'reschedule_select_time') {
            return $this->processTimeSelection($conversation, $message, $patient);
        }

        if ($state === 'reschedule_confirm') {
            return $this->confirmReschedule($conversation, $message, $patient, $platform);
        }

        // Default: show appointments to reschedule
        return $this->showAppointmentsToReschedule($conversation, $patient);
    }

    /**
     * Ask patient to identify themselves.
     */
    protected function askForPatientIdentification(ChatbotConversation $conversation, string $message): array
    {
        $patient = $this->tryIdentifyPatient($conversation);
        
        if ($patient) {
            return $this->showAppointmentsToReschedule($conversation, $patient);
        }

        return [
            'message' => "To reschedule an appointment, I need to identify your account first.\n\nPlease provide your phone number or email address:",
            'state' => 'awaiting_patient_identification',
        ];
    }

    /**
     * Show appointments that can be rescheduled.
     */
    protected function showAppointmentsToReschedule(ChatbotConversation $conversation, User $patient): array
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
                'message' => "You have no upcoming appointments to reschedule.\n\nWhat would you like to do instead?",
                'state' => 'idle',
            ];
        }

        $message = "🔄 Select an appointment to reschedule:\n\n";
        $buttons = [];

        foreach ($appointments as $index => $appointment) {
            $doctorName = $appointment->doctor?->user->name ?? 'Unknown';
            $date = $this->formatDate($appointment->appointment_date);
            $time = $this->formatTime($appointment->appointment_date);

            $message .= ($index + 1) . ". Dr. {$doctorName}\n";
            $message .= "   📅 {$date} at {$time}\n\n";

            $buttons[] = [
                'label' => 'Reschedule #' . ($index + 1),
                'payload' => "RESCHEDULE_APPT_{$appointment->id}",
            ];
        }

        $message .= "Reply with the appointment number or select an option below:";

        $conversation->updateState('reschedule_select_appointment', [
            'reschedulable_appointments' => $appointments->toArray(),
        ]);

        return [
            'message' => $message,
            'state' => 'reschedule_select_appointment',
            'quick_replies' => $buttons,
        ];
    }

    /**
     * Process appointment selection.
     */
    protected function processAppointmentSelection(ChatbotConversation $conversation, string $message, User $patient): array
    {
        $context = $conversation->context ?? [];
        $appointments = $context['reschedulable_appointments'] ?? [];

        $appointmentId = null;

        // Check if it's a number
        if ($this->isNumber(trim($message))) {
            $index = intval(trim($message)) - 1;
            if (isset($appointments[$index])) {
                $appointmentId = $appointments[$index]['id'];
            }
        }

        // Check if it's a quick reply payload
        if (str_starts_with($message, 'RESCHEDULE_APPT_')) {
            $appointmentId = str_replace('RESCHEDULE_APPT_', '', $message);
        }

        if (!$appointmentId) {
            return [
                'message' => "I couldn't identify that appointment. Please select from the list above:",
                'state' => 'reschedule_select_appointment',
            ];
        }

        $appointment = Appointment::with('doctor.user')->find($appointmentId);

        if (!$appointment || $appointment->patient_id !== $patient->id) {
            return [
                'message' => "Appointment not found. Please try again:",
                'state' => 'reschedule_select_appointment',
            ];
        }

        if (!$appointment->canBeRescheduled()) {
            return [
                'message' => "This appointment cannot be rescheduled. Please select another appointment:",
                'state' => 'reschedule_select_appointment',
            ];
        }

        // Ask for new date
        $conversation->updateState('reschedule_select_date', [
            'appointment_to_reschedule_id' => $appointmentId,
            'doctor_id' => $appointment->doctor_id,
        ]);

        return [
            'message' => "📅 Please enter a new date for your appointment:\n\n(e.g., 'tomorrow', 'Monday', '2026-04-15')",
            'state' => 'reschedule_select_date',
        ];
    }

    /**
     * Process date selection.
     */
    protected function processDateSelection(ChatbotConversation $conversation, string $message, User $patient): array
    {
        $context = $conversation->context ?? [];
        $doctorId = $context['doctor_id'] ?? null;

        if (!$doctorId) {
            return $this->showAppointmentsToReschedule($conversation, $patient);
        }

        $date = $this->parseDate($message);
        if (!$date) {
            return [
                'message' => "I couldn't understand that date. Please enter a valid date (e.g., 'tomorrow', 'Monday', '2026-04-15'):",
                'state' => 'reschedule_select_date',
            ];
        }

        if ($date->lt(now())) {
            return [
                'message' => "Please select a future date. The date you entered is in the past:",
                'state' => 'reschedule_select_date',
            ];
        }

        $doctor = Doctor::find($doctorId);
        if (!$doctor) {
            return [
                'message' => "Doctor not found. Let's start over.",
                'state' => 'idle',
            ];
        }

        // Get available slots
        $slots = $doctor->getAvailableSlots($date->format('Y-m-d'));

        if ($slots->isEmpty()) {
            return [
                'message' => "Dr. {$doctor->user->name} has no available slots on {$this->formatDate($date)}.\n\nPlease try another date:",
                'state' => 'reschedule_select_date',
            ];
        }

        // Format available slots
        $slotList = [];
        $buttons = [];
        $slotsArray = $slots->take(5)->values()->toArray();

        foreach ($slotsArray as $index => $slot) {
            $slotTime = \Carbon\Carbon::parse($slot['datetime']);
            $slotList[] = $this->formatTime($slotTime);
            $buttons[] = [
                'label' => $this->formatTime($slotTime),
                'payload' => "RESCHEDULE_TIME_{$slot['datetime']}",
            ];
        }

        $message = $this->formatList(
            "⏰ Available times on {$this->formatDate($date)}:",
            $slotList
        );
        $message .= "\n\nPlease select a time:";

        $conversation->updateState('reschedule_select_time', [
            'appointment_to_reschedule_id' => $context['appointment_to_reschedule_id'],
            'doctor_id' => $doctorId,
            'selected_date' => $date->format('Y-m-d'),
            'available_slots' => $slotsArray,
        ]);

        return [
            'message' => $message,
            'state' => 'reschedule_select_time',
            'quick_replies' => $buttons,
        ];
    }

    /**
     * Process time selection.
     */
    protected function processTimeSelection(ChatbotConversation $conversation, string $message, User $patient): array
    {
        $context = $conversation->context ?? [];
        $appointmentId = $context['appointment_to_reschedule_id'] ?? null;
        $availableSlots = $context['available_slots'] ?? [];

        $selectedSlot = null;

        // Check if it's a number
        if ($this->isNumber(trim($message))) {
            $index = intval(trim($message)) - 1;
            if (isset($availableSlots[$index])) {
                $selectedSlot = $availableSlots[$index];
            }
        }

        // Check if it's a quick reply payload
        if (str_starts_with($message, 'RESCHEDULE_TIME_')) {
            $timeStr = str_replace('RESCHEDULE_TIME_', '', $message);
            foreach ($availableSlots as $slot) {
                if ($slot['datetime'] === $timeStr) {
                    $selectedSlot = $slot;
                    break;
                }
            }
        }

        if (!$selectedSlot) {
            return [
                'message' => "I couldn't identify that time. Please select from the list above:",
                'state' => 'reschedule_select_time',
            ];
        }

        $appointment = Appointment::with('doctor.user')->find($appointmentId);
        $newTime = \Carbon\Carbon::parse($selectedSlot['datetime']);

        // Confirm reschedule details
        $conversation->updateState('reschedule_confirm', [
            'appointment_to_reschedule_id' => $appointmentId,
            'new_appointment_datetime' => $newTime->toDateTimeString(),
        ]);

        $oldDate = $this->formatDate($appointment->appointment_date);
        $oldTime = $this->formatTime($appointment->appointment_date);
        $newDate = $this->formatDate($newTime);
        $newTimeFormatted = $this->formatTime($newTime);

        return [
            'message' => "📋 Please confirm the reschedule:\n\n" .
                "*Current Appointment:*\n" .
                "📅 {$oldDate} at {$oldTime}\n\n" .
                "*New Appointment:*\n" .
                "📅 {$newDate} at {$newTimeFormatted}\n\n" .
                "Reply 'confirm' or 'yes' to reschedule, or 'cancel' to keep current appointment:",
            'state' => 'reschedule_confirm',
        ];
    }

    /**
     * Confirm and process reschedule.
     */
    protected function confirmReschedule(ChatbotConversation $conversation, string $message, User $patient, ChatbotPlatformInterface $platform): array
    {
        $context = $conversation->context ?? [];
        $appointmentId = $context['appointment_to_reschedule_id'] ?? null;
        $newDatetime = $context['new_appointment_datetime'] ?? null;

        if (!$appointmentId || !$newDatetime) {
            return [
                'message' => "Something went wrong. Let's start over.\n\nWhat would you like to do?",
                'state' => 'idle',
            ];
        }

        // Check if user confirmed
        if (!in_array(strtolower(trim($message)), ['confirm', 'yes', 'ok', 'reschedule'])) {
            return [
                'message' => "Reschedule cancelled. What would you like to do instead?",
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

            if (!$appointment->canBeRescheduled()) {
                DB::rollBack();
                return [
                    'message' => "This appointment cannot be rescheduled.",
                    'state' => 'idle',
                ];
            }

            // Re-validate slot availability to prevent double-booking
            $newTime = \Carbon\Carbon::parse($newDatetime);
            $doctor = $appointment->doctor;
            $availableSlots = $doctor->getAvailableSlots($newTime->format('Y-m-d'));
            $slotStillAvailable = $availableSlots->contains(fn($slot) => $slot['datetime'] === $newDatetime);
            
            if (!$slotStillAvailable) {
                DB::rollBack();
                return [
                    'message' => "Sorry, that time slot was just booked by someone else. Please choose another time.",
                    'state' => 'reschedule_select_time',
                ];
            }

            $appointment->update([
                'appointment_date' => $newDatetime,
                'appointment_end' => \Carbon\Carbon::parse($newDatetime)->copy()->addMinutes($doctor->appointment_duration),
                'status' => $doctor->auto_approve_appointments ? 'confirmed' : 'pending',
            ]);

            if ($doctor->auto_approve_appointments) {
                $appointment->confirm();
            }

            DB::commit();

            // Reset conversation
            $conversation->reset();

            return [
                'message' => "✅ Your appointment has been rescheduled successfully!\n\n" .
                    "📋 Appointment Number: {$appointment->appointment_number}\n" .
                    "📅 New Date: {$this->formatDate($appointment->appointment_date)}\n" .
                    "⏰ New Time: {$this->formatTime($appointment->appointment_date)}\n\n" .
                    "You will receive a confirmation notification shortly. Is there anything else I can help you with?",
                'state' => 'idle',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Chatbot appointment reschedule failed: ' . $e->getMessage(), [
                'appointment_id' => $appointmentId,
                'patient_id' => $patient->id,
            ]);

            return [
                'message' => "Sorry, there was an error rescheduling your appointment. Please try again or contact support.",
                'state' => 'idle',
            ];
        }
    }

    /**
     * Get the intent name.
     */
    public function getIntentName(): string
    {
        return 'reschedule_appointment';
    }
}
