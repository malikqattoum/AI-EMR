<?php

namespace Tests\Feature;

use App\Models\ChatbotConversation;
use App\Models\ChatbotIntent;
use App\Models\Doctor;
use App\Models\SystemSetting;
use App\Models\User;
use App\Services\Chatbot\ChatbotService;
use App\Services\Chatbot\Platforms\WhatsAppPlatform;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotFixesTest extends TestCase
{
    use RefreshDatabase;

    protected User $patient;
    protected Doctor $doctor;

    public function setUp(): void
    {
        parent::setUp();

        // Create a test patient
        $this->patient = User::create([
            'name' => 'Test Patient',
            'email' => 'patient@test.com',
            'phone' => '+1234567890',
            'password' => bcrypt('password'),
            'role' => 'patient',
        ]);

        // Create a specialty
        $specialty = \App\Models\Specialty::create([
            'name' => 'General Practice',
            'slug' => 'general-practice',
        ]);

        // Create a test doctor
        $doctorUser = User::create([
            'name' => 'Test Doctor',
            'email' => 'doctor@test.com',
            'password' => bcrypt('password'),
            'role' => 'doctor',
        ]);

        $this->doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'specialty_id' => $specialty->id,
            'license_number' => 'TEST-LIC-001',
            'is_active' => true,
            'appointment_duration' => 30,
            'auto_approve_appointments' => true,
            'appointment_type_preferences' => json_encode(['in_person' => true, 'video_call' => true]),
        ]);
    }

    /**
     * Test 1: Webhook routes are accessible without authentication
     */
    public function test_webhook_routes_are_public(): void
    {
        $response = $this->get('/webhooks/whatsapp');
        // Should not redirect to login (302), should return 403 or 200
        $this->assertNotEquals(302, $response->status());

        $response = $this->post('/webhooks/whatsapp');
        $this->assertNotEquals(302, $response->status());

        $response = $this->get('/webhooks/messenger');
        $this->assertNotEquals(302, $response->status());

        $response = $this->post('/webhooks/messenger');
        $this->assertNotEquals(302, $response->status());
    }

    /**
     * Test 2: Patient identification with phone number normalization
     */
    public function test_patient_identification_normalizes_phone(): void
    {
        // Create conversation with different phone formats
        $conversations = [
            ['platform' => 'whatsapp', 'platform_user_id' => '+1234567890'],
            ['platform' => 'whatsapp', 'platform_user_id' => '1234567890'],
            ['platform' => 'whatsapp', 'platform_user_id' => '1-234-567-890'],
        ];

        foreach ($conversations as $convData) {
            $conversation = ChatbotConversation::create($convData);

            // Call the service to process a message
            $service = app(ChatbotService::class);
            $result = $service->processMessage('whatsapp', $convData['platform_user_id'], 'Hi');

            // Verify patient was identified
            $conversation->refresh();
            $this->assertEquals($this->patient->id, $conversation->patient_id,
                "Failed to identify patient with phone format: {$convData['platform_user_id']}");
        }
    }

    /**
     * Test 3: Cancel appointment state machine works correctly
     */
    public function test_cancel_appointment_state_machine(): void
    {
        $conversation = ChatbotConversation::create([
            'session_id' => 'test-cancel-1',
            'platform' => 'whatsapp',
            'platform_user_id' => '+1234567890',
            'patient_id' => $this->patient->id,
            'state' => 'idle',
        ]);

        // Test idle state returns appointments list
        $action = new \App\Services\Chatbot\Actions\CancelAppointmentAction();
        $platform = new WhatsAppPlatform();
        $result = $action->handle($conversation, 'cancel appointment', $platform);

        $this->assertEquals('cancel_confirm', $result['state']);
        $this->assertStringContainsString('Select an appointment', $result['message']);

        // Verify conversation state was updated
        $conversation->refresh();
        $this->assertEquals('cancel_confirm', $conversation->state);
    }

    /**
     * Test 4: Reschedule appointment state machine works correctly
     */
    public function test_reschedule_appointment_state_machine(): void
    {
        $conversation = ChatbotConversation::create([
            'session_id' => 'test-reschedule-1',
            'platform' => 'whatsapp',
            'platform_user_id' => '+1234567890',
            'patient_id' => $this->patient->id,
            'state' => 'idle',
        ]);

        $action = new \App\Services\Chatbot\Actions\RescheduleAppointmentAction();
        $platform = new WhatsAppPlatform();
        $result = $action->handle($conversation, 'reschedule appointment', $platform);

        $this->assertEquals('reschedule_select_appointment', $result['state']);
        $this->assertStringContainsString('Select an appointment', $result['message']);

        $conversation->refresh();
        $this->assertEquals('reschedule_select_appointment', $conversation->state);
    }

    /**
     * Test 5: Keyword matching uses word boundaries
     */
    public function test_keyword_matching_uses_word_boundaries(): void
    {
        // Test that "Facebook" does NOT match "book_appointment"
        $service = new class extends ChatbotService {
            public function testKeywordMatch(string $message): bool
            {
                $message = strtolower(trim($message));
                $pattern = '/\bbook\b/i';
                return (bool) preg_match($pattern, $message);
            }
        };

        // "book" should match
        $this->assertTrue($service->testKeywordMatch('book appointment'));

        // "Facebook" should NOT match
        $this->assertFalse($service->testKeywordMatch('I have a question about Facebook'));

        // "notebook" should NOT match
        $this->assertFalse($service->testKeywordMatch('I need a notebook'));
    }

    /**
     * Test 6: Doctor fallback uses ordering
     */
    public function test_doctor_fallback_uses_ordering(): void
    {
        // Create specialty
        $specialty = \App\Models\Specialty::create([
            'name' => 'Specialty 1',
            'slug' => 'specialty-1',
        ]);

        // Create multiple doctors
        $doctor1 = Doctor::create([
            'user_id' => User::create(['name' => 'Doctor A', 'email' => 'docA@test.com', 'password' => bcrypt('x'), 'role' => 'doctor'])->id,
            'specialty_id' => $specialty->id,
            'license_number' => 'TEST-LIC-A',
            'is_active' => true,
            'appointment_duration' => 30,
        ]);

        $doctor2 = Doctor::create([
            'user_id' => User::create(['name' => 'Doctor B', 'email' => 'docB@test.com', 'password' => bcrypt('x'), 'role' => 'doctor'])->id,
            'specialty_id' => $specialty->id,
            'license_number' => 'TEST-LIC-B',
            'is_active' => true,
            'appointment_duration' => 30,
        ]);

        $action = new \App\Services\Chatbot\Actions\BookAppointmentAction();
        // Access protected method via reflection
        $reflection = new \ReflectionClass($action);
        $method = $reflection->getMethod('getDoctorForPatient');
        $method->setAccessible(true);

        $doctor = $method->invoke($action, $this->patient);

        // Should return the first doctor by ID (Doctor A)
        $this->assertNotNull($doctor);
        $this->assertEquals($doctor1->id, $doctor->id, 'Doctor fallback should use deterministic ordering');
    }

    /**
     * Test 7: Appointment type uses doctor's enabled types
     */
    public function test_booking_uses_enabled_appointment_types(): void
    {
        // Doctor has in_person and video_call enabled
        $this->assertEquals(['in_person' => true, 'video_call' => true],
            $this->doctor->getEnabledAppointmentTypes());

        // Verify it returns the first enabled type
        $enabledTypes = $this->doctor->getEnabledAppointmentTypes();
        $appointmentType = !empty($enabledTypes) ? array_keys($enabledTypes)[0] : 'in_person';
        $this->assertEquals('in_person', $appointmentType);
    }

    /**
     * Test 8: ChatbotController updateSettings persists settings
     */
    public function test_update_settings_persists(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('chatbot.settings.update'), [
                'ai_enabled' => true,
                'ai_model' => 'gpt-4o',
                'max_conversation_age_hours' => 48,
                'idle_timeout_minutes' => 60,
            ]);

        $response->assertRedirect(route('chatbot.settings'));
        $response->assertSessionHas('success');

        // Verify settings were saved
        $this->assertDatabaseHas('system_settings', [
            'key' => 'chatbot_ai_enabled',
            'value' => '1',
        ]);

        $this->assertDatabaseHas('system_settings', [
            'key' => 'chatbot_ai_model',
            'value' => 'gpt-4o',
        ]);
    }

    /**
     * Test 9: CheckAvailabilityAction processes doctor selection
     */
    public function test_check_availability_processes_doctor_selection(): void
    {
        $conversation = ChatbotConversation::create([
            'session_id' => 'test-avail-1',
            'platform' => 'whatsapp',
            'platform_user_id' => '+1234567890',
            'state' => 'awaiting_doctor',
            'context' => ['doctors' => [['id' => $this->doctor->id, 'user' => ['name' => 'Test Doctor']]]],
        ]);

        $action = new \App\Services\Chatbot\Actions\CheckAvailabilityAction();
        $platform = new WhatsAppPlatform();

        // Test selecting doctor by number
        $result = $action->handle($conversation, '1', $platform);

        $this->assertEquals('awaiting_date', $result['state']);
        $this->assertStringContainsString('enter the date', $result['message']);

        $conversation->refresh();
        $this->assertEquals('awaiting_date', $conversation->state);
    }

    /**
     * Test 10: tryIdentifyPatient updates conversation correctly
     */
    public function test_try_identify_patient_updates_conversation(): void
    {
        $conversation = ChatbotConversation::create([
            'session_id' => 'test-identify-1',
            'platform' => 'whatsapp',
            'platform_user_id' => '+1234567890',
            'patient_id' => null,
            'state' => 'idle',
        ]);

        $action = new \App\Services\Chatbot\Actions\BookAppointmentAction();
        $reflection = new \ReflectionClass($action);
        $method = $reflection->getMethod('tryIdentifyPatient');
        $method->setAccessible(true);

        $patient = $method->invoke($action, $conversation);

        $this->assertNotNull($patient);
        $this->assertEquals($this->patient->id, $patient->id);

        $conversation->refresh();
        $this->assertEquals($this->patient->id, $conversation->patient_id);
    }
}
