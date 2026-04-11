<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * VideoRecording Model
 *
 * Represents a recorded video consultation from a Daily.co video appointment.
 * Supports AI-powered transcription, summarization, and clinical analysis.
 *
 * @property int $id
 * @property int $appointment_id
 * @property int $doctor_id
 * @property int|null $patient_id
 * @property string|null $recording_id Daily.co recording ID
 * @property string|null $recording_url MP4 download URL
 * @property string|null $audio_recording_url Audio-only URL for transcription
 * @property int|null $duration Duration in seconds
 * @property int|null $file_size File size in bytes
 * @property string|null $resolution e.g., '1280x720'
 * @property string $status recording, processing, transcribing, ai_processing, ready, failed
 * @property \Carbon\Carbon|null $started_at
 * @property \Carbon\Carbon|null $ended_at
 * @property string|null $transcription
 * @property array|null $extracted_data symptoms, history, findings, medications, vitals, diagnosis, care_plan
 * @property string|null $ai_summary
 * @property string|null $ai_analysis
 * @property array|null $structured_chart
 * @property int|null $ai_assistant_result_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class VideoRecording extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'patient_id',
        'room_name',
        'recording_id',
        'audio_recording_id',
        'recording_url',
        'audio_recording_url',
        'duration',
        'file_size',
        'resolution',
        'status',
        'started_at',
        'ended_at',
        'transcription',
        'extracted_data',
        'ai_summary',
        'ai_analysis',
        'structured_chart',
        'ai_assistant_result_id',
    ];

    protected $casts = [
        'extracted_data' => 'array',
        'structured_chart' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Get the appointment this recording belongs to
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the doctor who conducted the consultation
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the patient (nullable for guest appointments)
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the AI assistant result linked to this recording
     */
    public function aiAssistantResult(): BelongsTo
    {
        return $this->belongsTo(AiAssistantResult::class, 'ai_assistant_result_id');
    }

    /**
     * Scope: recordings that are ready for playback
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    /**
     * Scope: recordings currently being processed
     */
    public function scopeProcessing($query)
    {
        return $query->whereIn('status', ['recording', 'processing', 'transcribing', 'ai_processing']);
    }

    /**
     * Scope: recordings that have failed processing
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: recordings for a specific doctor
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Check if recording is ready for AI processing
     */
    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    /**
     * Check if AI analysis has been completed
     */
    public function hasAiAnalysis(): bool
    {
        return $this->ai_assistant_result_id !== null;
    }

    /**
     * Get formatted duration (MM:SS)
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return '00:00';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
