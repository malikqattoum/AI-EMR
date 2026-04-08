<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatbotIntent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'description',
        'training_phrases',
        'responses',
        'action_handler',
        'enabled',
        'platforms',
        'priority',
        'metadata',
    ];

    protected $casts = [
        'training_phrases' => 'array',
        'responses' => 'array',
        'platforms' => 'array',
        'metadata' => 'array',
        'enabled' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Scope for enabled intents.
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Scope for specific platform.
     */
    public function scopeForPlatform($query, string $platform)
    {
        return $query->where(function($q) use ($platform) {
            $q->whereNull('platforms')
              ->orWhereJsonContains('platforms', $platform);
        });
    }

    /**
     * Scope ordered by priority.
     */
    public function scopeOrderedByPriority($query)
    {
        return $query->orderByDesc('priority');
    }

    /**
     * Check if this intent is available for a platform.
     */
    public function isAvailableForPlatform(string $platform): bool
    {
        if (is_null($this->platforms)) {
            return true; // null means all platforms
        }

        return in_array($platform, $this->platforms);
    }

    /**
     * Get a random response message.
     */
    public function getRandomResponse(): ?string
    {
        if (empty($this->responses)) {
            return null;
        }

        return $this->responses[array_rand($this->responses)];
    }

    /**
     * Get or create default intents.
     */
    public static function getDefaults(): array
    {
        return [
            [
                'name' => 'greeting',
                'label' => 'Greeting',
                'description' => 'User greets the bot',
                'training_phrases' => ['hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening', 'hola', 'start'],
                'responses' => [
                    "Hello! Welcome to MedCura AI Health Assistant. How can I help you today?\n\n1. Check Doctor Availability\n2. Book an Appointment\n3. View My Appointments\n4. Cancel an Appointment\n5. Reschedule an Appointment\n\nReply with a number or tell me what you need.",
                    "Hi there! I'm your MedCura AI health assistant. I can help you with:\n\n• Checking doctor availability\n• Booking appointments\n• Viewing your appointments\n• Canceling or rescheduling visits\n\nWhat would you like to do?",
                ],
                'action_handler' => null,
                'enabled' => true,
                'platforms' => null,
                'priority' => 10,
            ],
            [
                'name' => 'check_availability',
                'label' => 'Check Availability',
                'description' => 'User wants to check doctor availability',
                'training_phrases' => ['check availability', 'available slots', 'when is doctor available', 'free slots', 'open appointments', 'available times', 'schedule', '1'],
                'responses' => [],
                'action_handler' => \App\Services\Chatbot\Actions\CheckAvailabilityAction::class,
                'enabled' => true,
                'platforms' => null,
                'priority' => 50,
            ],
            [
                'name' => 'book_appointment',
                'label' => 'Book Appointment',
                'description' => 'User wants to book an appointment',
                'training_phrases' => ['book appointment', 'make appointment', 'schedule appointment', 'book a visit', 'i want to see a doctor', 'need appointment', '2', 'book'],
                'responses' => [],
                'action_handler' => \App\Services\Chatbot\Actions\BookAppointmentAction::class,
                'enabled' => true,
                'platforms' => null,
                'priority' => 50,
            ],
            [
                'name' => 'view_appointments',
                'label' => 'View Appointments',
                'description' => 'User wants to view their appointments',
                'training_phrases' => ['my appointments', 'view appointments', 'see appointments', 'upcoming appointments', 'my appointments list', '3', 'appointments'],
                'responses' => [],
                'action_handler' => \App\Services\Chatbot\Actions\ViewAppointmentsAction::class,
                'enabled' => true,
                'platforms' => null,
                'priority' => 50,
            ],
            [
                'name' => 'cancel_appointment',
                'label' => 'Cancel Appointment',
                'description' => 'User wants to cancel an appointment',
                'training_phrases' => ['cancel appointment', 'cancel my appointment', 'delete appointment', 'remove appointment', '4', 'cancel'],
                'responses' => [],
                'action_handler' => \App\Services\Chatbot\Actions\CancelAppointmentAction::class,
                'enabled' => true,
                'platforms' => null,
                'priority' => 50,
            ],
            [
                'name' => 'reschedule_appointment',
                'label' => 'Reschedule Appointment',
                'description' => 'User wants to reschedule an appointment',
                'training_phrases' => ['reschedule appointment', 'change appointment', 'reschedule my appointment', 'move appointment', '5', 'reschedule'],
                'responses' => [],
                'action_handler' => \App\Services\Chatbot\Actions\RescheduleAppointmentAction::class,
                'enabled' => true,
                'platforms' => null,
                'priority' => 50,
            ],
            [
                'name' => 'help',
                'label' => 'Help',
                'description' => 'User needs help',
                'training_phrases' => ['help', 'what can you do', 'options', 'menu', 'main menu', 'show menu', 'help me'],
                'responses' => [
                    "I can help you with:\n\n1️⃣ *Check Availability* - See when doctors are available\n2️⃣ *Book Appointment* - Schedule a new appointment\n3️⃣ *View Appointments* - See your upcoming appointments\n4️⃣ *Cancel Appointment* - Cancel an existing appointment\n5️⃣ *Reschedule Appointment* - Change your appointment time\n\nJust reply with a number or tell me what you'd like to do!",
                ],
                'action_handler' => null,
                'enabled' => true,
                'platforms' => null,
                'priority' => 20,
            ],
            [
                'name' => 'goodbye',
                'label' => 'Goodbye',
                'description' => 'User says goodbye',
                'training_phrases' => ['bye', 'goodbye', 'thank you', 'thanks', 'that is all', 'done', 'exit', 'quit'],
                'responses' => [
                    "You're welcome! If you need any further assistance, feel free to message me anytime. Take care! 👋",
                    "Thank you for using MedCura AI Health Assistant. Stay healthy! 😊",
                    "Goodbye! Remember, I'm here 24/7 if you need help with appointments. Have a great day!",
                ],
                'action_handler' => null,
                'enabled' => true,
                'platforms' => null,
                'priority' => 10,
            ],
        ];
    }
}
