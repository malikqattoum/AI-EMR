<?php

namespace App\Jobs;

use App\Models\VideoRecording;
use App\Services\VideoTranscriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessVideoRecordingTranscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 minutes
    public $tries = 2;

    protected VideoRecording $videoRecording;

    /**
     * Create a new job instance.
     */
    public function __construct(VideoRecording $videoRecording)
    {
        $this->videoRecording = $videoRecording;
    }

    /**
     * Execute the job.
     */
    public function handle(VideoTranscriptionService $transcriptionService): void
    {
        try {
            $this->videoRecording->update(['status' => 'transcribing']);

            Log::info('Starting video transcription processing', [
                'video_recording_id' => $this->videoRecording->id,
            ]);

            // Transcribe the audio from the recording
            $transcriptionResult = $transcriptionService->transcribeRecording($this->videoRecording);

            if (!$transcriptionResult['success']) {
                // Check if it's a "still processing" error - re-dispatch with delay
                if (str_contains($transcriptionResult['error'] ?? '', 'still processing')) {
                    Log::info('Transcription still processing, re-dispatching with delay', [
                        'video_recording_id' => $this->videoRecording->id,
                        'attempt' => $this->attempts(),
                    ]);
                    $this->release(30); // Release back to queue with 30 second delay
                    return;
                }
                throw new \Exception('Transcription failed: ' . ($transcriptionResult['error'] ?? 'Unknown error'));
            }

            // Store transcription
            $this->videoRecording->update([
                'transcription' => $transcriptionResult['text'] ?? null,
                'extracted_data' => $transcriptionResult['extracted_data'] ?? null,
                'status' => 'ai_processing',
            ]);

            Log::info('Video transcription completed', [
                'video_recording_id' => $this->videoRecording->id,
                'transcription_length' => strlen($transcriptionResult['text'] ?? ''),
            ]);

        } catch (\Exception $e) {
            Log::error('Video transcription processing failed', [
                'video_recording_id' => $this->videoRecording->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->videoRecording->update(['status' => 'failed']);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessVideoRecordingTranscription job failed', [
            'video_recording_id' => $this->videoRecording->id ?? null,
            'error' => $exception->getMessage(),
        ]);

        if (isset($this->videoRecording)) {
            $this->videoRecording->update(['status' => 'failed']);
        }
    }
}
