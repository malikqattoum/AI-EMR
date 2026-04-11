<?php

namespace App\Http\Controllers;

use App\Models\VideoRecording;
use App\Services\VideoAIAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class VideoRecordingController extends Controller
{
    protected VideoAIAnalysisService $aiAnalysisService;

    public function __construct(VideoAIAnalysisService $aiAnalysisService)
    {
        $this->aiAnalysisService = $aiAnalysisService;
    }

    /**
     * List all video recordings for the authenticated doctor
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'doctor') {
            abort(403, 'Only doctors can access video recordings');
        }

        $query = VideoRecording::with(['appointment.patient', 'doctor', 'patient'])
            ->where('doctor_id', Auth::id())
            ->latest();

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $recordings = $query->paginate(20)->withQueryString();

        return view('doctor.video-recordings.index', compact('recordings'));
    }

    /**
     * Show a specific video recording with playback and AI results
     */
    public function show(VideoRecording $recording)
    {
        $this->authorizeRecording($recording);

        $recording->load(['appointment.patient', 'doctor', 'patient', 'aiAssistantResult']);

        return view('doctor.video-recordings.show', compact('recording'));
    }

    /**
     * Get the playback URL for a recording
     */
    public function playback(VideoRecording $recording)
    {
        $this->authorizeRecording($recording);

        if (!$recording->recording_url) {
            return response()->json(['error' => 'Recording URL not available'], 404);
        }

        return response()->json([
            'success' => true,
            'recording_url' => $recording->recording_url,
            'audio_recording_url' => $recording->audio_recording_url,
            'duration' => $recording->duration,
            'resolution' => $recording->resolution,
            'file_size' => $recording->file_size,
        ]);
    }

    /**
     * Generate AI summary for a video recording
     */
    public function generateSummary(VideoRecording $recording)
    {
        $this->authorizeRecording($recording);

        if (!$recording->transcription) {
            return response()->json([
                'error' => 'Transcription not yet available',
                'status' => $recording->status,
            ], 400);
        }

        $result = $this->aiAnalysisService->generateSummary($recording);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'summary' => $result['summary'],
            ]);
        }

        return response()->json([
            'error' => $result['error'] ?? 'Failed to generate summary',
        ], 500);
    }

    /**
     * Generate AI analysis for a video recording
     */
    public function generateAnalysis(VideoRecording $recording)
    {
        $this->authorizeRecording($recording);

        if (!$recording->transcription) {
            return response()->json([
                'error' => 'Transcription not yet available',
                'status' => $recording->status,
            ], 400);
        }

        $result = $this->aiAnalysisService->generateAnalysis($recording);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'analysis' => $result['analysis'],
                'extracted_data' => $result['extracted_data'],
            ]);
        }

        return response()->json([
            'error' => $result['error'] ?? 'Failed to generate analysis',
        ], 500);
    }

    /**
     * Download the recording file
     */
    public function download(VideoRecording $recording)
    {
        $this->authorizeRecording($recording);

        if (!$recording->recording_url) {
            abort(404, 'Recording URL not available');
        }

        // Redirect to the Daily.co recording URL
        return redirect()->away($recording->recording_url);
    }

    /**
     * Delete a video recording
     */
    public function destroy(VideoRecording $recording)
    {
        $this->authorizeRecording($recording);

        // Delete the AI result if it exists
        if ($recording->aiAssistantResult) {
            $recording->aiAssistantResult->delete();
        }

        $recording->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recording deleted successfully',
        ]);
    }

    /**
     * Authorize that the current user owns the recording
     */
    protected function authorizeRecording(VideoRecording $recording): void
    {
        $user = Auth::user();

        if ($user->role !== 'doctor' || $user->id !== $recording->doctor_id) {
            abort(403, 'Unauthorized to access this recording');
        }
    }
}
