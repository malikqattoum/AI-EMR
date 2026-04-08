<?php

namespace App\Services\Chatbot\Actions;

use App\Models\Appointment;
use App\Models\ChatbotConversation;
use App\Models\Doctor;
use App\Models\User;
use App\Services\Chatbot\ChatbotActionHandler;
use App\Services\Chatbot\Platforms\ChatbotPlatformInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookAppointmentAction extends ChatbotActionHandler
{
    /**
     * Handle the book appointment action.
     */
    public function handle(ChatbotConversation $conversation, string $message, ChatbotPlatformInterface $platform, array $context = []): array
    {
        $state = $conversation->state;
        $patient = $conversation->patient;

        // If patient not identified, ask for phone number
        if (!$patient) {
            return $this->askForPatientIdentification($conversation, $message);
        }

        // State machine for booking flow
        if ($state === 'idle' || $state === 'booking_select_doctor') {
            return $this->askForDoctor($conversation, $patient);
        }

        if ($state === 'booking_select_date') {
            return $this->processDoctorSelection($conversation, $message, $patient);
        }

        if ($state === 'booking_select_time') {
            return $this->processDateSelection($conversation, $message, $patient);
        }

        if ($state === 'booking_confirm') {
            return $this->processTimeSelection($conversation, $message, $patient);
        }

        if ($state === 'booking_final_confirm') {
            return $this->confirmBooking($conversation, $message, $patient, $platform);
        }

        // Default: start the flow
        return $this->askForDoctor($conversation, $patient);
    }

    /**
     * Ask patient to identify themselves.
     */
    protected function askForPatientIdentification(ChatbotConversation $conversation, string $message): array
    {
        // Try to identify patient automatically
        $patient = $this->tryIdentifyPatient($conversation);
        
        if ($patient) {
            return $this->askForDoctor($conversation, $patient);
        }

        return [
            'message' => "To book an appointment, I need to identify your account first.\n\nPlease provide your phone number or email address associated with your account:",
            'state' => 'awaiting_patient_identification',
        ];
    }

    /**
     * Ask user to select a doctor.
     */
    protected function askForDoctor(ChatbotConversation $conversation, User $patient): array
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

        $message = "👨‍⚕️ Please select a doctor:\n\n";
        $buttons = [];

        foreach ($doctors as $index => $doctor) {
            $specialty = $doctor->specialty?->name ?? 'General';
            $message .= ($index + 1) . ". Dr. {$doctor->user->name} - {$specialty}\n";
            $buttons[] = [
                'label' => 'Dr. ' . $doctor->user->name,
                'payload' => "DOCTOR_{$doctor->id}",
            ];
        }

        $message .= "\nReply with the doctor's number or name:";

        $conversation->updateState('booking_select_date', [
            'doctors' => $doctors->toArray(),
        ]);

        return [
            'message' => $message,
            'state' => 'booking_select_date',
            'quick_replies' => $buttons,
        ];
    }

    /**
     * Process doctor selection.
     */
    protected function processDoctorSelection(ChatbotConversation $conversation, string $message, User $patient): array
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
                'state' => 'booking_select_date',
            ];
        }

        $doctor = Doctor::with('user')->find($doctorId);
        if (!$doctor) {
            return [
                'message' => "Doctor not found. Please try again:",
                'state' => 'booking_select_date',
            ];
        }

        // Ask for date
        $conversation->updateState('booking_select_time', [
            'selected_doctor_id' => $doctorId,
            'doctor_name' => $doctor->user->name,
        ]);

        return [
            'message' => "📅 Great! Now please enter the date you'd like to book with Dr. {$doctor->user->name}:\n\n(e.g., 'tomorrow', 'Monday', '2026-04-15')",
            'state' => 'booking_select_time',
        ];
    }

    /**
     * Process date selection.
     */
    protected function processDateSelection(ChatbotConversation $conversation, string $message, User $patient): array
    {
        $context = $conversation->context ?? [];
        $doctorId = $context['selected_doctor_id'] ?? null;

        if (!$doctorId) {
            return $this->askForDoctor($conversation, $patient);
        }

        $date = $this->parseDate($message);
        if (!$date) {
            return [
                'message' => "I couldn't understand that date. Please enter a valid date (e.g., 'tomorrow', 'Monday', '2026-04-15'):",
                'state' => 'booking_select_time',
            ];
        }

        if ($date->lt(now())) {
            return [
                'message' => "Please select a future date. The date you entered is in the past:",
                'state' => 'booking_select_time',
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
                'state' => 'booking_select_time',
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
                'payload' => "TIME_{$slot['datetime']}",
            ];
        }

        $message = $this->formatList(
            "⏰ Available times for Dr. {$doctor->user->name} on {$this->formatDate($date)}:",
            $slotList
        );
        $message .= "\n\nPlease select a time:";

        $conversation->updateState('booking_confirm', [
            'selected_doctor_id' => $doctorId,
            'doctor_name' => $doctor->user->name,
            'selected_date' => $date->format('Y-m-d'),
            'available_slots' => $slotsArray,
        ]);

        return [
            'message' => $message,
            'state' => 'booking_confirm',
            'quick_replies' => $buttons,
        ];
    }

    /**
     * Process time selection and confirm booking.
     */
    protected function processTimeSelection(ChatbotConversation $conversation, string $message, User $patient): array
    {
        $context = $conversation->context ?? [];
        $doctorId = $context['selected_doctor_id'] ?? null;
        $selectedDate = $context['selected_date'] ?? null;
        $availableSlots = $context['available_slots'] ?? [];

        // Try to parse time selection
        $selectedSlot = null;

        // Check if it's a number
        if ($this->isNumber(trim($message))) {
            $index = intval(trim($message)) - 1;
            if (isset($availableSlots[$index])) {
                $selectedSlot = $availableSlots[$index];
            }
        }

        // Check if it's a quick reply payload
        if (str_starts_with($message, 'TIME_')) {
            $timeStr = str_replace('TIME_', '', $message);
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
                'state' => 'booking_confirm',
            ];
        }

        $doctor = Doctor::find($doctorId);
        $appointmentTime = \Carbon\Carbon::parse($selectedSlot['datetime']);

        // Confirm booking details
        $conversation->updateState('booking_final_confirm', [
            'selected_doctor_id' => $doctorId,
            'doctor_name' => $doctor->user->name,
            'selected_date' => $selectedDate,
            'selected_time' => $appointmentTime->toDateTimeString(),
            'appointment_datetime' => $appointmentTime->toDateTimeString(),
        ]);

        return [
            'message' => "📋 Please confirm your appointment details:\n\n" .
                "👨‍⚕️ Doctor: Dr. {$doctor->user->name}\n" .
                "📅 Date: {$this->formatDate($appointmentTime)}\n" .
                "⏰ Time: {$this->formatTime($appointmentTime)}\n\n" .
                "Reply 'confirm' or 'yes' to book this appointment, or 'cancel' to start over:",
            'state' => 'booking_final_confirm',
        ];
    }

    /**
     * Confirm and create the booking.
     */
    protected function confirmBooking(ChatbotConversation $conversation, string $message, User $patient, ChatbotPlatformInterface $platform): array
    {
        $context = $conversation->context ?? [];
        $doctorId = $context['selected_doctor_id'] ?? null;
        $appointmentDatetime = $context['appointment_datetime'] ?? null;

        if (!$doctorId || !$appointmentDatetime) {
            return [
                'message' => "Something went wrong. Let's start over.\n\nWhat would you like to do?",
                'state' => 'idle',
            ];
        }

        // Check if user confirmed
        if (!in_array(strtolower(trim($message)), ['confirm', 'yes', 'ok', 'book', 'book it'])) {
            return [
                'message' => "Booking cancelled. What would you like to do instead?",
                'state' => 'idle',
            ];
        }

        try {
            DB::beginTransaction();

            $doctor = Doctor::find($doctorId);
            if (!$doctor) {
                DB::rollBack();
                return [
                    'message' => "Doctor not found. Please try again.",
                    'state' => 'idle',
                ];
            }

            // Re-validate slot availability to prevent double-booking
            $appointmentTime = \Carbon\Carbon::parse($appointmentDatetime);
            $availableSlots = $doctor->getAvailableSlots($appointmentTime->format('Y-m-d'));
            $slotStillAvailable = $availableSlots->contains(fn($slot) => $slot['datetime'] === $appointmentDatetime);
            
            if (!$slotStillAvailable) {
                DB::rollBack();
                return [
                    'message' => "Sorry, that time slot was just booked by someone else. Please choose another time.",
                    'state' => 'booking_select_time',
                ];
            }

            // Create appointment
            // Use doctor's first enabled appointment type
            $enabledTypes = $doctor->getEnabledAppointmentTypes();
            $appointmentType = !empty($enabledTypes) ? $enabledTypes[0] : 'in_person';

            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctorId,
                'appointment_date' => $appointmentDatetime,
                'appointment_end' => \Carbon\Carbon::parse($appointmentDatetime)->copy()->addMinutes($doctor->appointment_duration),
                'status' => $doctor->auto_approve_appointments ? 'confirmed' : 'pending',
                'appointment_type' => $appointmentType,
                'reason' => 'Booked via chatbot',
            ]);

            if ($doctor->auto_approve_appointments) {
                $appointment->confirm();
            }

            DB::commit();

            // Reset conversation
            $conversation->reset();

            $status = ucfirst($appointment->status);
            return [
                'message' => "✅ Appointment booked successfully!\n\n" .
                    "📋 Appointment Number: {$appointment->appointment_number}\n" .
                    "👨‍⚕️ Doctor: Dr. {$doctor->user->name}\n" .
                    "📅 Date: {$this->formatDate($appointment->appointment_date)}\n" .
                    "⏰ Time: {$this->formatTime($appointment->appointment_date)}\n" .
                    "📌 Status: {$status}\n\n" .
                    "You will receive a confirmation notification shortly. Is there anything else I can help you with?",
                'state' => 'idle',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Chatbot appointment booking failed: ' . $e->getMessage(), [
                'patient_id' => $patient->id,
                'doctor_id' => $doctorId,
                'appointment_datetime' => $appointmentDatetime,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'message' => "Sorry, there was an error booking your appointment. Please try again or contact support.\n\nWhat would you like to do?",
                'state' => 'idle',
            ];
        }
    }

    /**
     * Get the intent name.
     */
    public function getIntentName(): string
    {
        return 'book_appointment';
    }
}
