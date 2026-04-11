<?php

namespace Tests\Unit\Config;

use Tests\TestCase;

class ConfigFixesTest extends TestCase
{
    /**
     * Test CORS configuration
     */
    public function test_cors_has_restricted_methods()
    {
        $methods = config('cors.allowed_methods');
        
        $this->assertIsArray($methods);
        $this->assertContains('GET', $methods);
        $this->assertContains('POST', $methods);
        $this->assertContains('PUT', $methods);
        $this->assertContains('PATCH', $methods);
        $this->assertContains('DELETE', $methods);
        $this->assertContains('OPTIONS', $methods);
        $this->assertNotContains('*', $methods, 'Should not allow all methods');
    }

    public function test_cors_has_common_headers()
    {
        $headers = config('cors.allowed_headers');
        
        $this->assertIsArray($headers);
        $this->assertContains('Content-Type', $headers);
        $this->assertContains('Authorization', $headers);
        $this->assertContains('X-CSRF-TOKEN', $headers);
        $this->assertContains('Accept', $headers, 'Should include Accept header');
        $this->assertContains('Cache-Control', $headers, 'Should include Cache-Control');
    }

    public function test_cors_origins_configurable_via_env()
    {
        $origins = config('cors.allowed_origins');
        
        $this->assertIsArray($origins);
        // Default should be ['*'] from env
        $this->assertContains('*', $origins);
    }

    public function test_cors_supports_credentials_defaults_false()
    {
        $supportsCredentials = config('cors.supports_credentials');
        
        $this->assertFalse($supportsCredentials, 'Should default to false for security');
    }

    /**
     * Test Session configuration
     */
    public function test_session_encryption_defaults_true()
    {
        $encrypt = config('session.encrypt');
        
        $this->assertTrue($encrypt, 'Session encryption should default to true');
    }

    public function test_session_secure_cookie_defaults_true()
    {
        $secure = config('session.secure');
        
        $this->assertTrue($secure, 'Secure cookie should default to true');
    }

    public function test_session_same_site_config_set_to_strict()
    {
        // Verify the config file has strict as the default
        $configFile = config_path('session.php');
        $this->assertFileExists($configFile);
        
        $configContent = file_get_contents($configFile);
        $this->assertStringContainsString("'same_site' => env('SESSION_SAME_SITE', 'strict')", $configContent,
            'session.php config should have strict as default');
    }
}
