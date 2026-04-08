<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chatbot_intents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // check_availability, book_appointment, view_appointments, cancel_appointment, reschedule_appointment, greeting, help, goodbye
            $table->string('label');
            $table->text('description')->nullable();
            $table->json('training_phrases')->nullable(); // Sample utterances for AI training
            $table->json('responses')->nullable(); // Default responses
            $table->string('action_handler')->nullable(); // Action handler class name
            $table->boolean('enabled')->default(true);
            $table->json('platforms')->nullable(); // ['whatsapp', 'messenger'] - null means all platforms
            $table->integer('priority')->default(0); // Higher priority intents are matched first
            $table->json('metadata')->nullable(); // Additional configuration
            $table->timestamps();

            $table->index('name');
            $table->index('enabled');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_intents');
    }
};
