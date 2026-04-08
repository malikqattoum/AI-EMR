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
        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('chatbot_conversations')->onDelete('cascade');
            $table->string('direction'); // inbound, outbound
            $table->text('content');
            $table->string('message_type')->default('text'); // text, quick_reply, button, image, etc.
            $table->json('payload')->nullable(); // Platform-specific payload
            $table->string('status')->default('sent'); // sent, delivered, read, failed
            $table->string('platform_message_id')->nullable(); // External platform message ID
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('direction');
            $table->index('message_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_messages');
    }
};
