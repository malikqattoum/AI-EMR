<?php

namespace App\Http\Controllers;

use App\Models\VideoRecording;
use App\Jobs\ProcessVideoRecordingTranscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * VideoRecordingWebhookController
 * 
 * Handles Daily.co webhooks for recording events.
 * When Daily.co finishes processing a recording, it sends a webhook notification.
 * This controller updates the recording status and triggers AI processing.
 */
class VideoRecordingWebhookController extends Controller
{
    /**
     * Handle Daily.co webhook events
     */
    public function handle(Request $request)
    {
        $event = $request->input('event');
        
        Log::info('Daily.co webhook received', [
            'event' => $event,
            'payload' => $request->all()
        ]);

        switch ($event) {
            case 'recording.ready':
                $this->handleRecordingReady($request);
                break;

            case 'recording.error':
                $this->handleRecordingError($request);
                break;

            default:
                Log::info('Unhandled Daily.co webhook event', ['event' => $event]);
                break;
        }

        return response()->json(['success' => true]);
    }

    /**
     * Handle recording.ready event
     * Daily.co has finished processing the recording and it's available for download
     */
    protected function handleRecordingReady(Request $request)
    {
        $payload = $request->all();
        $recordingId = $payload['recording_id'] ?? null;
        $roomName = $payload['room_name'] ?? null;
        $recordingType = $payload['type'] ?? 'cloud';

        if (!$recordingId) {
            Log::warning('Daily.co webhook missing recording_id', ['payload' => $payload]);
            return;
        }

        // Try to find the VideoRecording by recording_id or audio_recording_id
        $videoRecording = VideoRecording::where('recording_id', $recordingId)
            ->orWhere('audio_recording_id', $recordingId)
            ->first();

        // Fallback: match by room_name (more reliable than appointment_id)
        if (!$videoRecording && $roomName) {
            $videoRecording = VideoRecording::where('room_name', $roomName)
                ->where('status', 'recording')
                ->latest()
                ->first();
        }

        if (!$videoRecording) {
            Log::warning('VideoRecording not found for Daily.co webhook', [
                'recording_id' => $recordingId,
                'room_name' => $roomName
            ]);
            return;
        }

        // Extract recording URLs and metadata
        $recordingUrl = $payload['recording_url'] ?? null;
        $duration = $payload['duration'] ?? null;
        $fileSize = $payload['size'] ?? null;
        $resolution = $payload['resolution'] ?? null;

        // Determine if this is the audio-only recording or the main video recording
        $isAudioOnly = $recordingType === 'cloud-audio-only';

        // Update the appropriate fields based on recording type
        $updateData = [];

        if ($isAudioOnly) {
            $updateData['audio_recording_url'] = $recordingUrl;
        } else {
            $updateData['recording_url'] = $recordingUrl;
            if ($duration) $updateData['duration'] = $duration;
            if ($fileSize) $updateData['file_size'] = $fileSize;
            if ($resolution) $updateData['resolution'] = $resolution;
        }

        $videoRecording->update($updateData);

        Log::info('VideoRecording updated for recording.ready', [
            'video_recording_id' => $videoRecording->id,
            'recording_type' => $recordingType,
            'recording_id' => $recordingId,
        ]);

        // Only dispatch transcription job when BOTH recordings are ready
        // Refresh to get latest state after this update
        $videoRecording->refresh();

        if ($videoRecording->recording_url && $videoRecording->audio_recording_url) {
            Log::info('Both recordings ready, dispatching transcription job', [
                'video_recording_id' => $videoRecording->id,
            ]);
            ProcessVideoRecordingTranscription::dispatch($videoRecording);
        } else {
            Log::info('Waiting for second recording before dispatching transcription', [
                'video_recording_id' => $videoRecording->id,
                'has_video_url' => !empty($videoRecording->recording_url),
                'has_audio_url' => !empty($videoRecording->audio_recording_url),
            ]);
        }
    }

    /**
     * Handle recording.error event
     */
    protected function handleRecordingError(Request $request)
    {
        $payload = $request->all();
        $recordingId = $payload['recording_id'] ?? null;
        $error = $payload['error'] ?? 'Unknown error';

        Log::error('Daily.co recording error', [
            'recording_id' => $recordingId,
            'error' => $error,
            'payload' => $payload
        ]);

        if ($recordingId) {
            VideoRecording::where('recording_id', $recordingId)
                ->update([
                    'status' => 'failed',
                ]);
        }
    }
}
