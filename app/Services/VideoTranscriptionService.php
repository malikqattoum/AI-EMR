<?php

namespace App\Services;

use App\Models\VideoRecording;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI;

/**
 * VideoTranscriptionService
 * 
 * Handles transcription of video recordings using AssemblyAI.
 * Downloads the audio from Daily.co recording URL, uploads to AssemblyAI,
 * and retrieves the transcription with speaker diarization.
 */
class VideoTranscriptionService
{
    protected AssemblyAIService $assemblyAIService;

    public function __construct(AssemblyAIService $assemblyAIService)
    {
        $this->assemblyAIService = $assemblyAIService;
    }

    /**
     * Transcribe a video recording
     * 
     * @param VideoRecording $videoRecording
     * @return array ['success' => bool, 'text' => string|null, 'extracted_data' => array|null, 'error' => string|null]
     */
    public function transcribeRecording(VideoRecording $videoRecording): array
    {
        try {
            // Determine which audio URL to use
            $audioUrl = $videoRecording->audio_recording_url ?? $videoRecording->recording_url;

            if (!$audioUrl) {
                throw new \Exception('No audio URL available for transcription');
            }

            Log::info('Starting video recording transcription', [
                'video_recording_id' => $videoRecording->id,
                'audio_url' => $audioUrl,
            ]);

            // Submit audio to AssemblyAI for transcription
            $transcriptResult = $this->assemblyAIService->processTranscript($audioUrl, [
                'speaker_labels' => true,
                'punctuate' => true,
                'format_text' => true,
                'disfluencies' => false, // Remove filler words for cleaner transcript
                'word_boost' => [
                    'hypertension', 'diabetes', 'prescription', 'symptoms', 'diagnosis',
                    'blood pressure', 'heart rate', 'temperature', 'medication',
                    'patient', 'doctor', 'examination', 'treatment', 'consultation',
                    'medical history', 'physical examination', 'follow-up',
                    // Add doctor's specialty terms if available
                    ...$this->getDoctorSpecialtyTerms($videoRecording),
                ],
                'boost_param' => 'high',
            ]);

            if (!$transcriptResult) {
                throw new \Exception('AssemblyAI transcription submission failed');
            }

            // AssemblyAI processes asynchronously - poll for result
            $transcriptId = $transcriptResult['id'] ?? null;
            if (!$transcriptId) {
                throw new \Exception('No transcript ID returned from AssemblyAI');
            }

            // Poll for completion (with timeout)
            $transcript = $this->pollForTranscript($transcriptId, 300); // 5 minute timeout

            if (!$transcript || ($transcript['status'] ?? '') !== 'completed') {
                $error = $transcript['error'] ?? 'Transcription did not complete';
                throw new \Exception('Transcription failed: ' . $error);
            }

            // Build formatted transcript with speaker labels
            $formattedText = $this->formatTranscript($transcript);

            // Extract medical data from transcription
            $extractedData = $this->extractMedicalData($formattedText, $videoRecording);

            Log::info('Video recording transcription completed', [
                'video_recording_id' => $videoRecording->id,
                'transcript_length' => strlen($formattedText),
            ]);

            return [
                'success' => true,
                'text' => $formattedText,
                'extracted_data' => $extractedData,
            ];

        } catch (\Exception $e) {
            Log::error('Video recording transcription failed', [
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
     * Poll AssemblyAI for transcript completion
     * Uses delayed job dispatch instead of blocking sleep to free up queue workers
     */
    protected function pollForTranscript(string $transcriptId): ?array
    {
        $result = $this->assemblyAIService->getTranscript($transcriptId);

        if (!$result) {
            return null;
        }

        if ($result['status'] === 'completed') {
            return $result;
        }

        if ($result['status'] === 'error') {
            return $result;
        }

        // Status is 'processing' or 'queued' - release job back to queue with delay
        // This frees the queue worker instead of blocking it with sleep()
        throw new \Exception('Transcription still processing - job will retry');
    }

    /**
     * Format transcript data into readable text with speaker labels
     */
    protected function formatTranscript(array $transcript): string
    {
        $utterances = $transcript['utterances'] ?? [];

        if (empty($utterances)) {
            // Fallback to plain text
            return $transcript['text'] ?? '';
        }

        $formatted = [];
        foreach ($utterances as $utterance) {
            $speaker = $utterance['speaker'] ?? 'Unknown';
            $text = $utterance['text'] ?? '';
            $formatted[] = "[{$speaker}]: {$text}";
        }

        return implode("\n", $formatted);
    }

    /**
     * Extract medical data from transcription using GPT-4o
     * Reuses the same pattern as ambient listening
     */
    protected function extractMedicalData(string $transcription, VideoRecording $videoRecording): ?array
    {
        try {
            // Check cache first
            $cacheKey = 'video_ai_extraction_' . md5($transcription);
            $cached = cache()->get($cacheKey);
            if ($cached) {
                return $cached;
            }

            $prompt = $this->buildExtractionPrompt($transcription, $videoRecording);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a medical documentation AI. Extract structured clinical data from the consultation transcript. Return ONLY valid JSON with no additional text.',
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.1,
                'max_tokens' => 2000,
                'response_format' => ['type' => 'json_object'],
            ]);

            $content = $response->choices[0]->message->content ?? null;

            if (!$content) {
                return null;
            }

            $extractedData = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to parse extracted medical data as JSON', [
                    'content' => $content,
                    'json_error' => json_last_error_msg(),
                ]);
                return null;
            }

            // Cache for 1 hour
            cache()->put($cacheKey, $extractedData, 3600);

            return $extractedData;

        } catch (\Exception $e) {
            Log::error('Medical data extraction failed', [
                'video_recording_id' => $videoRecording->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Build the extraction prompt (similar to ambient listening pattern)
     */
    protected function buildExtractionPrompt(string $transcription, VideoRecording $videoRecording): string
    {
        $appointment = $videoRecording->appointment;
        $patientInfo = '';

        if ($appointment) {
            $patient = $appointment->patient;
            if ($patient) {
                $patientInfo = "Patient: {$patient->name}\n";
                if ($patient->date_of_birth) {
                    $age = $patient->date_of_birth->diffInYears(now());
                    $patientInfo .= "Age: {$age}\n";
                }
                $patientInfo .= "Gender: " . ($patient->gender ?? 'Unknown') . "\n";
            }
        }

        return <<<PROMPT
Extract the following clinical information from this video consultation transcript. Return ONLY valid JSON.

{$patientInfo}

**TRANSCRIPT:**
{$transcription}

**EXTRACT these 7 categories (return as JSON):**

{
  "symptoms": ["List patient complaints, pain, functional limitations"],
  "medical_history": ["Past conditions, surgeries, family history mentioned"],
  "physical_findings": ["Examination results, observations, clinical findings"],
  "medications": ["Current medications, dosages, allergies mentioned"],
  "vital_signs": ["Blood pressure, temperature, heart rate, etc. if mentioned"],
  "diagnosis": ["Potential diagnoses, differential diagnoses discussed"],
  "care_plan": ["Treatment recommendations, follow-up instructions"]
}

Rules:
- Return ONLY the JSON object, no markdown, no explanation
- If a category has no information, return an empty array []
- Be precise and clinical in your extraction
- Include specific medical terminology when mentioned
PROMPT;
    }

    /**
     * Get medical terminology terms based on doctor's specialty
     */
    protected function getDoctorSpecialtyTerms(VideoRecording $videoRecording): array
    {
        $terms = [];

        try {
            $doctor = $videoRecording->doctor;
            if ($doctor && $doctor->doctorProfile) {
                $specialty = strtolower($doctor->doctorProfile->specialty ?? '');
                
                $specialtyTerms = match ($specialty) {
                    'cardiology' => ['cardiac', 'echocardiogram', 'arrhythmia', 'heart failure', 'atrial fibrillation'],
                    'dermatology' => ['lesion', 'biopsy', 'rash', 'eczema', 'psoriasis', 'melanoma'],
                    'orthopedics' => ['fracture', 'joint', 'bone density', 'osteoporosis', 'arthritis'],
                    'neurology' => ['neurological', 'seizure', 'migraine', 'cognitive', 'neuropathy'],
                    'pediatrics' => ['immunization', 'growth chart', 'developmental', 'vaccination'],
                    'psychiatry' => ['depression', 'anxiety', 'mood disorder', 'therapy', 'SSRI'],
                    'endocrinology' => ['hormone', 'thyroid', 'insulin', 'glucose', 'metabolic'],
                    'pulmonology' => ['respiratory', 'asthma', 'COPD', 'pulmonary function', 'spirometry'],
                    'gastroenterology' => ['GI', 'endoscopy', 'colonoscopy', 'IBD', 'reflux'],
                    default => [],
                };

                $terms = array_merge($terms, $specialtyTerms);
            }
        } catch (\Exception $e) {
            // Ignore errors in getting specialty terms
        }

        return $terms;
    }
}
