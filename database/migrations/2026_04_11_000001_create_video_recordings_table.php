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
        Schema::create('video_recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Daily.co recording details
            $table->string('recording_id')->nullable()->unique(); // Daily.co recording ID
            $table->string('audio_recording_id')->nullable()->unique(); // Daily.co audio-only recording ID
            $table->string('room_name')->nullable(); // Daily.co room name for webhook matching
            $table->string('recording_url')->nullable(); // MP4 download URL
            $table->string('audio_recording_url')->nullable(); // Audio-only URL for transcription
            $table->unsignedInteger('duration')->nullable(); // Duration in seconds
            $table->unsignedBigInteger('file_size')->nullable(); // File size in bytes
            $table->string('resolution')->nullable(); // e.g., '1280x720'
            
            // Status tracking
            $table->string('status')->default('recording'); // recording, processing, transcribing, ai_processing, ready, failed
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            
            // Transcription & AI results
            $table->longText('transcription')->nullable();
            $table->json('extracted_data')->nullable(); // symptoms, history, findings, medications, vitals, diagnosis, care_plan
            $table->longText('ai_summary')->nullable();
            $table->longText('ai_analysis')->nullable();
            $table->json('structured_chart')->nullable();
            $table->foreignId('ai_assistant_result_id')->nullable()->constrained('ai_assistant_results')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['appointment_id', 'status']);
            $table->index(['doctor_id', 'created_at']);
            $table->index('recording_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_recordings');
    }
};
