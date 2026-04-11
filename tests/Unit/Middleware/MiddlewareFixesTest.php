<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\LocalhostMiddleware;
use App\Http\Middleware\KioskSessionIsolation;
use App\Http\Middleware\MedicalAudioSecurity;
use App\Models\User;
use App\Models\VoiceTranscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class MiddlewareFixesTest extends TestCase
{
    /**
     * Test LocalhostMiddleware handles forwarded IPs
     */
    public function test_localhost_middleware_allows_forwarded_localhost()
    {
        $middleware = new LocalhostMiddleware();

        // Test direct localhost
        $request = Request::create('/debug', 'GET');
        $request->server->set('REMOTE_ADDR', '127.0.0.1');

        $next = function ($req) {
            return response('OK');
        };

        $response = $middleware->handle($request, $next);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_localhost_middleware_blocks_non_localhost()
    {
        $middleware = new LocalhostMiddleware();

        $request = Request::create('/debug', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        $next = function ($req) {
            return response('OK');
        };

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $middleware->handle($request, $next);
    }

    public function test_localhost_middleware_handles_x_forwarded_for()
    {
        $middleware = new LocalhostMiddleware();

        $request = Request::create('/debug', 'GET');
        $request->server->set('REMOTE_ADDR', '10.0.0.1');
        $request->headers->set('X-Forwarded-For', '127.0.0.1');

        $next = function ($req) {
            return response('OK');
        };

        $response = $middleware->handle($request, $next);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test KioskSessionIsolation regenerates token
     */
    public function test_kiosk_session_isolation_regenerates_token()
    {
        // This test verifies the middleware includes regenerateToken()
        $middleware = new KioskSessionIsolation();

        // Use reflection to check the method contains regenerateToken
        $reflection = new \ReflectionClass($middleware);
        $method = $reflection->getMethod('isolateKioskSession');
        $method->setAccessible(true);

        $filename = $method->getFileName();
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();
        $content = file_get_contents($filename);
        $methodContent = implode("\n", array_slice(explode("\n", $content), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString('regenerateToken', $methodContent, 
            'KioskSessionIsolation should regenerate session token after flush');
    }

    /**
     * Test MedicalAudioSecurity validates role properly
     */
    public function test_medical_audio_security_validates_role()
    {
        $middleware = new MedicalAudioSecurity();

        // Use reflection to test validateMedicalAccess
        $reflection = new \ReflectionClass($middleware);
        $method = $reflection->getMethod('validateMedicalAccess');
        $method->setAccessible(true);

        // Create non-doctor user
        $nonDoctor = User::factory()->create([
            'role' => 'patient',
        ]);

        $this->actingAs($nonDoctor);

        $request = Request::create('/audio', 'GET');

        $result = $method->invoke($middleware, $request);
        $this->assertFalse($result, 'Patient should not have medical audio access');
    }

    public function test_medical_audio_security_allows_doctor()
    {
        $middleware = new MedicalAudioSecurity();

        $reflection = new \ReflectionClass($middleware);
        $method = $reflection->getMethod('validateMedicalAccess');
        $method->setAccessible(true);

        // Create doctor user
        $doctor = User::factory()->create([
            'role' => 'doctor',
        ]);

        $this->actingAs($doctor);

        $request = Request::create('/audio', 'GET');

        $result = $method->invoke($middleware, $request);
        $this->assertTrue($result, 'Doctor should have medical audio access');
    }

    public function test_medical_audio_security_validates_session_ownership()
    {
        $middleware = new MedicalAudioSecurity();

        $reflection = new \ReflectionClass($middleware);
        $method = $reflection->getMethod('validateMedicalAccess');
        $method->setAccessible(true);

        // Create two doctors
        $doctor1 = User::factory()->create(['role' => 'doctor']);
        $doctor2 = User::factory()->create(['role' => 'doctor']);

        // Create transcription for doctor1
        $transcription = VoiceTranscription::create([
            'session_id' => 'test-session-123',
            'doctor_id' => $doctor1->id,
            'status' => 'completed',
            'raw_transcription' => 'Test',
        ]);

        // Try to access as doctor2
        $this->actingAs($doctor2);

        $request = Request::create('/audio', 'GET', ['session_id' => 'test-session-123']);

        $result = $method->invoke($middleware, $request);
        $this->assertFalse($result, 'Doctor2 should not access doctor1 transcription');
    }

    public function test_medical_audio_security_allows_owner_access()
    {
        $middleware = new MedicalAudioSecurity();

        $reflection = new \ReflectionClass($middleware);
        $method = $reflection->getMethod('validateMedicalAccess');
        $method->setAccessible(true);

        $doctor = User::factory()->create(['role' => 'doctor']);

        VoiceTranscription::create([
            'session_id' => 'test-session-456',
            'doctor_id' => $doctor->id,
            'status' => 'completed',
            'raw_transcription' => 'Test',
        ]);

        $this->actingAs($doctor);

        $request = Request::create('/audio', 'GET', ['session_id' => 'test-session-456']);

        $result = $method->invoke($middleware, $request);
        $this->assertTrue($result, 'Doctor should access own transcription');
    }
}
