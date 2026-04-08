<?php

namespace Database\Seeders;

use App\Models\ChatbotIntent;
use Illuminate\Database\Seeder;

class ChatbotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default intents
        $defaultIntents = ChatbotIntent::getDefaults();

        foreach ($defaultIntents as $intentData) {
            ChatbotIntent::updateOrCreate(
                ['name' => $intentData['name']],
                $intentData
            );
        }

        $this->command->info('Chatbot intents seeded successfully.');
    }
}
