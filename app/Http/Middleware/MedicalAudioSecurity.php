<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MedicalAudioSecurity
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Verify user has permission for this medical visit
        if (!$this->validateMedicalAccess($request)) {
            Log::warning('Unauthorized medical audio access attempt', [
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // 2. Encrypt all audio data in transit (Enforce HTTPS/WSS)
        if (!$request->isSecure() && app()->environment('production')) {
            return response()->json(['error' => 'Secure connection required'], 403);
        }
        $request->headers->set('X-Required-Encryption', 'TLS_1_3');

        // 3. Implement automatic data retention policy
        $this->applyRetentionPolicy($request);

        // 4. Audit log all access
        Log::info('Medical Audio Access', [
            'user_id' => auth()->id(),
            'action' => 'audio_access',
            'visit_id' => $request->visit_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return $next($request);
    }

    /**
     * Validate that the user has permission to access medical audio for this visit.
     */
    private function validateMedicalAccess($request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();

        // Check if user is a doctor, admin, or authorized staff
        $hasRole = $user->role === 'doctor' || $user->role === 'admin' || $user->role === 'hospital_admin';
        if (!$hasRole) {
            return false;
        }

        // If session_id is present, verify the transcription belongs to this doctor
        if ($request->has('session_id')) {
            $transcription = \App\Models\VoiceTranscription::where('session_id', $request->session_id)->first();

            // If transcription exists, verify doctor ownership
            if ($transcription && $transcription->doctor_id !== Auth::id()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Apply audio retention policy configuration.
     * Actual deletion is handled by a scheduled command.
     */
    private function applyRetentionPolicy($request): void
    {
        // Set retention policy configuration (actual enforcement via scheduled task)
        config(['medical.audio_retention_hours' => 72]);
    }
}
