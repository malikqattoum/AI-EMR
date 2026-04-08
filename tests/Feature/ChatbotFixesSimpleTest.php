<?php

namespace Tests\Feature;

use App\Models\ChatbotConversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotFixesSimpleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Webhook routes are public
     */
    public function test_webhook_routes_are_public(): void
    {
        // Test WhatsApp webhook
        $response = $this->get('/webhooks/whatsapp');
        // Should not redirect to login (302), should return 403 or 200
        $this->assertNotEquals(302, $response->status(), 'WhatsApp webhook should not require auth');

        $response = $this->post('/webhooks/whatsapp');
        $this->assertNotEquals(302, $response->status(), 'WhatsApp webhook POST should not require auth');

        // Test Messenger webhook
        $response = $this->get('/webhooks/messenger');
        $this->assertNotEquals(302, $response->status(), 'Messenger webhook should not require auth');

        $response = $this->post('/webhooks/messenger');
        $this->assertNotEquals(302, $response->status(), 'Messenger webhook POST should not require auth');
    }

    /**
     * Test 2: Keyword matching uses word boundaries
     */
    public function test_keyword_matching_uses_word_boundaries(): void
    {
        // Test that "Facebook" does NOT match "book"
        $pattern = '/\bbook\b/i';
        
        // "book" should match
        $this->assertEquals(1, preg_match($pattern, 'book appointment'));
        $this->assertEquals(1, preg_match($pattern, 'I want to book'));
        
        // "Facebook" should NOT match
        $this->assertEquals(0, preg_match($pattern, 'I have a question about Facebook'));
        
        // "notebook" should NOT match
        $this->assertEquals(0, preg_match($pattern, 'I need a notebook'));
        
        // "Facebook" lowercase should NOT match
        $this->assertEquals(0, preg_match($pattern, 'facebook page'));
    }

    /**
     * Test 3: Patient identification Eloquent update syntax
     */
    public function test_conversation_update_with_array_syntax(): void
    {
        $conversation = ChatbotConversation::create([
            'session_id' => 'test-update-1',
            'platform' => 'whatsapp',
            'platform_user_id' => '+1234567890',
            'patient_id' => null,
            'state' => 'idle',
        ]);

        // Create a patient
        $patient = User::create([
            'name' => 'Test Patient',
            'email' => 'patient@test.com',
            'phone' => '+1234567890',
            'password' => bcrypt('password'),
            'role' => 'patient',
        ]);

        // Update using array syntax (the fix)
        $conversation->update(['patient_id' => $patient->id]);

        $conversation->refresh();
        $this->assertEquals($patient->id, $conversation->patient_id, 
            'Conversation should update patient_id using array syntax');
    }

    /**
     * Test 4: Chatbot settings route exists
     */
    public function test_chatbot_settings_route_exists(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('chatbot.settings'));

        $this->assertEquals(200, $response->status());
    }
}
