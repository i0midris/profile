<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_security_headers_are_sent(): void
    {
        $response = $this->get('/admin/login');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString('https://analytics.ahrefs.com', $csp);
    }

    public function test_www_redirects_permanently_to_bare_domain(): void
    {
        $this->get('http://www.albaseetsoft-ye.com/ar/services?x=1')
            ->assertStatus(301)
            ->assertRedirect('http://albaseetsoft-ye.com/ar/services?x=1');
    }
}
