<?php

namespace App\Services;

use App\Models\VideoRecording;
use App\Models\AiAssistantResult;
use Illuminate\Support\Facades\Log;
use OpenAI;

/**
 * VideoAIAnalysisService
 * 
 * Provides AI summarization and clinical analysis for video consultation recordings.
 * Mirrors the pattern used in ambient listening (VoiceAssistantController).
 */
class VideoAIAnalysisService
{
    /**
     * Generate AI summary for a video recording
     * 
     * @param VideoRecording $videoRecording
     * @return array ['success' => bool, 'summary' => string|null, 'error' => string|null]
     */
    public function generateSummary(VideoRecording $videoRecording): array
    {
        try {
            if (!$videoRecording->transcription) {
                throw new \Exception('No transcription available for summarization');
            }

            // Check cache
            $cacheKey = 'video_ai_summary_' . md5($videoRecording->transcription);
            $cached = cache()->get($cacheKey);
            if ($cached) {
                return [
                    'success' => true,
                    'summary' => $cached,
                ];
            }

            $prompt = $this->buildSummaryPrompt($videoRecording);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a medical documentation assistant. Summarize the video consultation into clear, concise clinical notes. Focus on key medical information.',
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.3,
                'max_tokens' => 1500,
            ]);

            $summary = $response->choices[0]->message->content ?? null;

            if (!$summary) {
                throw new \Exception('Failed to generate summary');
            }

            // Store in database
            $videoRecording->update(['ai_summary' => $summary]);

            // Cache for 2 hours
            cache()->put($cacheKey, $summary, 7200);

            Log::info('Video AI summary generated', [
                'video_recording_id' => $videoRecording->id,
                'summary_length' => strlen($summary),
            ]);

            return [
                'success' => true,
                'summary' => $summary,
            ];

        } catch (\Exception $e) {
            Log::error('Video AI summary generation failed', [
                'video_recording_id' => $videoRecording->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate comprehensive AI analysis for a video recording
     * Similar to VoiceAssistantController::generateAIAnalysis
     * 
     * @param VideoRecording $videoRecording
     * @return array ['success' => bool, 'analysis' => string|null, 'extracted_data' => array|null, 'ai_result' => AiAssistantResult|null, 'error' => string|null]
     */
    public function generateAnalysis(VideoRecording $videoRecording): array
    {
        try {
            if (!$videoRecording->transcription) {
                throw new \Exception('No transcription available for analysis');
            }

            // Check if already has analysis
            if ($videoRecording->hasAiAnalysis()) {
                return [
                    'success' => true,
                    'analysis' => $videoRecording->ai_analysis,
                    'extracted_data' => $videoRecording->extracted_data,
                    'ai_result' => $videoRecording->aiAssistantResult,
                ];
            }

            // Check cache
            $cacheKey = 'video_ai_analysis_' . md5($videoRecording->transcription . ($videoRecording->appointment?->doctor_notes ?? ''));
            $cached = cache()->get($cacheKey);
            if ($cached) {
                return [
                    'success' => true,
                    'analysis' => $cached['analysis'],
                    'extracted_data' => $cached['extracted_data'],
                    'ai_result' => null,
                ];
            }

            // Extraction was already performed during transcription by ProcessVideoRecordingTranscription
            $extractedData = $videoRecording->extracted_data;

            $prompt = $this->buildAnalysisPrompt($videoRecording);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert medical AI assistant. Analyze the video consultation transcript and provide a comprehensive clinical analysis. Follow evidence-based medicine principles.',
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.3,
                'max_tokens' => 2500,
            ]);

            $analysis = $response->choices[0]->message->content ?? null;

            if (!$analysis) {
                throw new \Exception('Failed to generate analysis');
            }

            // Store in database
            $videoRecording->update(['ai_analysis' => $analysis]);

            // Create AiAssistantResult record (same pattern as ambient listening)
            $aiResult = $this->createAiResult($videoRecording, $analysis);

            // Link back to VideoRecording
            $videoRecording->update([
                'ai_assistant_result_id' => $aiResult->id,
                'status' => 'ready', // Final status after AI processing
            ]);

            // Cache for 2 hours
            $cacheData = [
                'analysis' => $analysis,
                'extracted_data' => $extractedData,
            ];
            cache()->put($cacheKey, $cacheData, 7200);

            Log::info('Video AI analysis generated', [
                'video_recording_id' => $videoRecording->id,
                'ai_result_id' => $aiResult->id,
                'analysis_length' => strlen($analysis),
            ]);

            return [
                'success' => true,
                'analysis' => $analysis,
                'extracted_data' => $extractedData,
                'ai_result' => $aiResult,
            ];

        } catch (\Exception $e) {
            Log::error('Video AI analysis generation failed', [
                'video_recording_id' => $videoRecording->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create AiAssistantResult record linked to the video recording
     */
    protected function createAiResult(VideoRecording $videoRecording, string $analysis): AiAssistantResult
    {
        $appointment = $videoRecording->appointment;

        return AiAssistantResult::create([
            'doctor_id' => $videoRecording->doctor_id,
            'patient_id' => $videoRecording->patient_id,
            'source' => 'video_recording',
            'ai_analysis' => $analysis,
            'patient_data' => $appointment ? [
                'appointment_id' => $appointment->id,
                'appointment_date' => $appointment->appointment_date?->toIso8601String(),
                'appointment_type' => $appointment->appointment_type,
                'reason' => $appointment->reason,
                'symptoms' => $appointment->symptoms,
            ] : [],
            'voice_transcript' => $videoRecording->transcription,
            'voice_file_path' => $videoRecording->recording_url,
            'session_id' => 'video-' . $videoRecording->id,
            'status' => 'pending',
            'usage_data' => [
                'source' => 'video_consultation',
                'duration' => $videoRecording->duration,
                'recording_id' => $videoRecording->recording_id,
            ],
        ]);
    }

    /**
     * Build the summary prompt
     */
    protected function buildSummaryPrompt(VideoRecording $videoRecording): string
    {
        $appointment = $videoRecording->appointment;
        $contextInfo = '';

        if ($appointment) {
            $contextInfo .= "**Appointment Context:**\n";
            if ($appointment->reason) {
                $contextInfo .= "- Reason for visit: {$appointment->reason}\n";
            }
            if ($appointment->symptoms) {
                $contextInfo .= "- Reported symptoms: {$appointment->symptoms}\n";
            }
            if ($appointment->doctor_notes) {
                $contextInfo .= "- Doctor notes: {$appointment->doctor_notes}\n";
            }
            $contextInfo .= "\n";
        }

        return <<<PROMPT
{$contextInfo}**VIDEO CONSULTATION TRANSCRIPT:**
{$videoRecording->transcription}

Please provide a concise clinical summary of this video consultation. Include:

1. **Chief Complaint**: Primary reason for the visit
2. **History of Present Illness**: Key details about the patient's condition
3. **Relevant Medical History**: Past conditions or medications mentioned
4. **Examination Findings**: Physical exam or observations
5. **Assessment**: Diagnoses or clinical impressions
6. **Plan**: Treatment recommendations and follow-up

Keep the summary concise, professional, and clinically relevant. Use standard medical documentation format.
PROMPT;
    }

    /**
     * Build the comprehensive analysis prompt
     */
    protected function buildAnalysisPrompt(VideoRecording $videoRecording): string
    {
        $appointment = $videoRecording->appointment;
        $patientInfo = '';

        if ($appointment && $appointment->patient) {
            $patient = $appointment->patient;
            $patientInfo = "**Patient Information:**\n";
            $patientInfo .= "- Name: {$patient->name}\n";
            if ($patient->date_of_birth) {
                $age = $patient->date_of_birth->diffInYears(now());
                $patientInfo .= "- Age: {$age}\n";
            }
            $patientInfo .= "- Gender: " . ($patient->gender ?? 'Unknown') . "\n";
            if ($patient->blood_type) {
                $patientInfo .= "- Blood Type: {$patient->blood_type}\n";
            }
            $patientInfo .= "\n";
        }

        $extractedData = $videoRecording->extracted_data;
        $extractedDataJson = $extractedData ? json_encode($extractedData, JSON_PRETTY_PRINT) : 'Not yet extracted';

        return <<<PROMPT
{$patientInfo}**VIDEO CONSULTATION TRANSCRIPT:**
{$videoRecording->transcription}

**PRELIMINARY EXTRACTED DATA:**
{$extractedDataJson}

Provide a comprehensive clinical analysis of this video consultation. Your analysis should include:

### 1. CLINICAL SUMMARY
- Chief complaint and history of present illness
- Relevant past medical history
- Review of systems (if applicable)

### 2. PHYSICAL EXAMINATION
- Key examination findings
- Vital signs (if mentioned)
- Relevant positive and negative findings

### 3. ASSESSMENT
- Primary diagnosis/diagnoses
- Differential diagnoses considered
- Clinical reasoning

### 4. INVESTIGATIONS
- Lab tests or imaging ordered/recommended
- Relevant previous results discussed

### 5. TREATMENT PLAN
- Medications prescribed or adjusted
- Non-pharmacological recommendations
- Patient education provided
- Follow-up plan

### 6. RISK CONSIDERATIONS
- Red flags or warning signs
- Potential complications
- Risk factors identified

### 7. EVIDENCE-BASED RECOMMENDATIONS
- Additional considerations based on current clinical guidelines
- Quality of care observations

Be thorough, evidence-based, and clinically precise. Identify any gaps in the consultation that may warrant further investigation.
PROMPT;
    }
}
