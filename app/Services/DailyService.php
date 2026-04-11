<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DailyService
{
    protected $apiKey;
    protected $domain;
    protected $baseUrl = 'https://api.daily.co/v1';

    public function __construct()
    {
        $this->apiKey = config('daily.api_key');
        $this->domain = config('daily.domain');
    }

    /**
     * Create a pre-configured HTTP client with auth headers
     */
    protected function makeRequest()
    {
        return Http::timeout(30)->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Create a video room
     */
    public function createRoom($roomName, $expiresInMinutes = 60)
    {
        try {
            $response = $this->makeRequest()->post($this->baseUrl . '/rooms', [
                'name' => $roomName,
                'properties' => [
                    'exp' => time() + ($expiresInMinutes * 60),
                    'max_participants' => 2,
                    'enable_screenshare' => true,
                    'enable_chat' => false,
                    'enable_knocking' => false,
                    'start_video_off' => false,
                    'start_audio_off' => false,
                ]
            ]);

            if ($response->failed()) {
                throw new \Exception('Daily.co API error: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            \Log::error('Daily.co createRoom error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get room details
     */
    public function getRoom($roomName)
    {
        $response = $this->makeRequest()->get($this->baseUrl . '/rooms/' . $roomName);

        return $response->json();
    }

    /**
     * Delete a room
     */
    public function deleteRoom($roomName)
    {
        $response = $this->makeRequest()->delete($this->baseUrl . '/rooms/' . $roomName);

        return $response->json();
    }

    /**
     * Create a meeting token for a participant
     */
    public function createMeetingToken($roomName, $userName, $isOwner = false)
    {
        try {
            $response = $this->makeRequest()
                ->retry(2, 100)
                ->post($this->baseUrl . '/meeting-tokens', [
                    'properties' => [
                        'room_name' => $roomName,
                        'user_name' => $userName,
                        'is_owner' => $isOwner,
                        'enable_screenshare' => true,
                        'start_video_off' => false,
                        'start_audio_off' => false,
                    ]
                ]);

            if ($response->failed()) {
                \Log::error('Daily.co API failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Daily.co API error: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            \Log::error('Daily.co createMeetingToken error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Start cloud recording for a room
     *
     * @param string $roomName The room name to start recording
     * @param string $recordingType Type of recording: 'cloud', 'cloud-audio-only', or 'raw-tracks'
     * @return array Recording response from Daily.co
     */
    public function startRecording($roomName, $recordingType = 'cloud')
    {
        try {
            $response = $this->makeRequest()->post($this->baseUrl . "/rooms/{$roomName}/recordings/start", [
                'version' => 3,
                'type' => $recordingType,
            ]);

            if ($response->failed()) {
                throw new \Exception('Daily.co start recording error: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            \Log::error('Daily.co startRecording error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Stop cloud recording for a room
     *
     * @param string $roomName The room name to stop recording
     * @return array Recording response from Daily.co
     */
    public function stopRecording($roomName)
    {
        try {
            $response = $this->makeRequest()->post($this->baseUrl . "/rooms/{$roomName}/recordings/stop");

            if ($response->failed()) {
                throw new \Exception('Daily.co stop recording error: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            \Log::error('Daily.co stopRecording error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get recording details by recording ID
     *
     * @param string $recordingId The Daily.co recording ID
     * @return array Recording details
     */
    public function getRecording($recordingId)
    {
        try {
            $response = $this->makeRequest()->get($this->baseUrl . "/recordings/{$recordingId}");

            if ($response->failed()) {
                throw new \Exception('Daily.co get recording error: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            \Log::error('Daily.co getRecording error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * List recordings for a room
     *
     * @param string $roomName The room name
     * @return array List of recordings
     */
    public function listRecordings($roomName = null)
    {
        try {
            $response = $this->makeRequest()
                ->when($roomName, fn($q) => $q->withQuery(['room_name' => $roomName]))
                ->get($this->baseUrl . '/recordings');

            if ($response->failed()) {
                throw new \Exception('Daily.co list recordings error: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            \Log::error('Daily.co listRecordings error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update recording properties
     *
     * @param string $recordingId The Daily.co recording ID
     * @param array $properties Properties to update (e.g., expire_at)
     * @return array Updated recording response
     */
    public function updateRecording($recordingId, array $properties = [])
    {
        try {
            $response = $this->makeRequest()->patch($this->baseUrl . "/recordings/{$recordingId}", $properties);

            if ($response->failed()) {
                throw new \Exception('Daily.co update recording error: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            \Log::error('Daily.co updateRecording error: ' . $e->getMessage());
            throw $e;
        }
    }
}
