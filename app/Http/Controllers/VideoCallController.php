<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\VideoRecording;
use App\Services\DailyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoCallController extends Controller
{
    protected $dailyService;

    public function __construct(DailyService $dailyService)
    {
        $this->dailyService = $dailyService;
    }

    /**
     * Get patient phone number
     */
    public function getPatientPhone($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        // Only doctor can view patient phone
        if (Auth::id() !== $appointment->doctor->user_id) {
            abort(403);
        }

        $patientPhone = $appointment->patient_phone;

        if (!$patientPhone) {
            return response()->json(['error' => 'Patient phone number not available'], 400);
        }

        return response()->json([
            'success' => true,
            'phone' => $patientPhone,
            'patient_name' => $appointment->patient->name
        ]);
    }

    /**
     * Generate video token for appointment
     */
    public function generateVideoToken(Request $request, $appointmentId)
    {
        try {
            $appointment = Appointment::findOrFail($appointmentId);

            if (Auth::id() !== $appointment->doctor->user_id && Auth::id() !== $appointment->patient_id) {
                abort(403);
            }

            $roomName = 'appointment-' . $appointmentId;

            // Create room
            $room = $this->dailyService->createRoom($roomName, 120);

            $appointment->update([
                'meeting_link' => route('video.room', ['appointment' => $appointmentId]),
                'meeting_id' => $roomName
            ]);

            return response()->json([
                'roomUrl' => $room['url'],
                'roomName' => $roomName
            ]);
        } catch (\Exception $e) {
            \Log::error('Video token error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to create video room',
                'message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * End video call
     */
    public function endVideoCall($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        if (Auth::id() !== $appointment->doctor->user_id) {
            abort(403);
        }

        if ($appointment->meeting_id) {
            $this->dailyService->deleteRoom($appointment->meeting_id);
        }

        $appointment->update(['status' => 'completed']);

        return response()->json(['success' => true]);
    }

    /**
     * Start video recording for an appointment
     * Doctor only - starts Daily.co cloud recording
     */
    public function startRecording($appointmentId)
    {
        try {
            $appointment = Appointment::with('videoRecording')->findOrFail($appointmentId);

            // Only doctor can start recording
            if (Auth::id() !== $appointment->doctor->user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Check if already recording
            if ($appointment->videoRecording && $appointment->videoRecording->status === 'recording') {
                return response()->json([
                    'success' => true,
                    'message' => 'Recording already started',
                    'recording' => $appointment->videoRecording
                ]);
            }

            $roomName = $appointment->meeting_id ?? 'appointment-' . $appointmentId;

            // Start Daily.co cloud recording (video + audio)
            $recordingResponse = $this->dailyService->startRecording($roomName, 'cloud');

            // Also start audio-only recording for transcription
            // If this fails, cleanup the first recording to avoid orphaned resources
            $audioRecordingResponse = null;
            try {
                $audioRecordingResponse = $this->dailyService->startRecording($roomName, 'cloud-audio-only');
            } catch (\Exception $e) {
                \Log::warning('Failed to start audio-only recording, cleaning up video recording', [
                    'appointment_id' => $appointmentId,
                    'error' => $e->getMessage(),
                ]);
                try {
                    $this->dailyService->stopRecording($roomName);
                } catch (\Exception $cleanupError) {
                    \Log::error('Failed to cleanup video recording after audio recording failure', [
                        'appointment_id' => $appointmentId,
                        'error' => $cleanupError->getMessage(),
                    ]);
                }
                throw $e;
            }

            // Create VideoRecording record with both recording IDs
            $videoRecording = VideoRecording::create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $appointment->doctor->user_id,
                'patient_id' => $appointment->patient_id,
                'room_name' => $roomName,
                'recording_id' => $recordingResponse['id'] ?? null,
                'audio_recording_id' => $audioRecordingResponse['id'] ?? null,
                'status' => 'recording',
                'started_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recording started',
                'recording' => $videoRecording,
                'daily_recording_id' => $recordingResponse['id'] ?? null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Start recording error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to start recording',
                'message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Stop video recording for an appointment
     * Doctor only - stops Daily.co cloud recording
     */
    public function stopRecording($appointmentId)
    {
        try {
            $appointment = Appointment::with('videoRecording')->findOrFail($appointmentId);

            // Only doctor can stop recording
            if (Auth::id() !== $appointment->doctor->user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $videoRecording = $appointment->videoRecording;

            if (!$videoRecording || $videoRecording->status !== 'recording') {
                return response()->json(['error' => 'No active recording found'], 404);
            }

            $roomName = $appointment->meeting_id ?? 'appointment-' . $appointmentId;

            // Stop Daily.co recording
            $this->dailyService->stopRecording($roomName);

            // Update recording status
            $videoRecording->update([
                'status' => 'processing',
                'ended_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recording stopped. Processing will complete in the background.',
                'recording' => $videoRecording
            ]);
        } catch (\Exception $e) {
            \Log::error('Stop recording error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to stop recording',
                'message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get recording status for an appointment
     */
    public function getRecordingStatus($appointmentId)
    {
        try {
            $appointment = Appointment::with('videoRecording')->findOrFail($appointmentId);

            // Only doctor or patient can check status
            if (Auth::id() !== $appointment->doctor->user_id && Auth::id() !== $appointment->patient_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $videoRecording = $appointment->videoRecording;

            if (!$videoRecording) {
                return response()->json([
                    'success' => true,
                    'has_recording' => false,
                ]);
            }

            return response()->json([
                'success' => true,
                'has_recording' => true,
                'recording' => [
                    'id' => $videoRecording->id,
                    'status' => $videoRecording->status,
                    'started_at' => $videoRecording->started_at,
                    'ended_at' => $videoRecording->ended_at,
                    'duration' => $videoRecording->duration,
                    'formatted_duration' => $videoRecording->formatted_duration,
                    'has_ai_analysis' => $videoRecording->hasAiAnalysis(),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Get recording status error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to get recording status',
                'message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
