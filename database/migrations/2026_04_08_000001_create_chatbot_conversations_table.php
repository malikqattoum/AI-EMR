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
        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id')->unique();
            $table->foreignId('patient_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('platform'); // whatsapp, messenger
            $table->string('platform_user_id')->nullable(); // WhatsApp number or Messenger PSID
            $table->string('state')->default('idle'); // idle, awaiting_doctor, awaiting_date, awaiting_time, booking_confirm, etc.
            $table->json('context')->nullable(); // Store temporary data like selected_doctor_id, selected_date, etc.
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['platform', 'platform_user_id']);
            $table->index(['patient_id', 'platform']);
            $table->index('state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_conversations');
    }
};
