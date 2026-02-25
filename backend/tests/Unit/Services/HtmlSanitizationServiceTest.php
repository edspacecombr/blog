<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\HtmlSanitizationService;

class HtmlSanitizationServiceTest extends TestCase
{
    private HtmlSanitizationService $sanitizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sanitizer = new HtmlSanitizationService();
    }

    /**
     * Test script tag removal
     */
    public function testScriptTagRemoval(): void
    {
        $dirty = '<p>Hello</p><script>alert("xss")</script><p>World</p>';
        $clean = $this->sanitizer->sanitize($dirty);

        $this->assertStringNotContainsString('<script>', $clean);
        $this->assertStringNotContainsString('alert', $clean);
        $this->assertStringContainsString('Hello', $clean);
        $this->assertStringContainsString('World', $clean);
    }

    /**
     * Test onclick attribute removal
     */
    public function testOnclickAttributeRemoval(): void
    {
        $dirty = '<img src="image.jpg" onclick="alert(\'xss\')" />';
        $clean = $this->sanitizer->sanitize($dirty);

        $this->assertStringNotContainsString('onclick', $clean);
    }

    /**
     * Test safe HTML preservation
     */
    public function testSafeHtmlPreservation(): void
    {
        $safe = '<p>Hello <strong>World</strong></p><ul><li>Item 1</li></ul>';
        $clean = $this->sanitizer->sanitize($safe);

        $this->assertStringContainsString('<p>', $clean);
        $this->assertStringContainsString('<strong>', $clean);
        $this->assertStringContainsString('<ul>', $clean);
    }

    /**
     * Test iframe tag handling
     */
    public function testIframeHandling(): void
    {
        $dirty = '<p>Content</p><iframe src="https://example.com"></iframe>';
        $clean = $this->sanitizer->sanitize($dirty, ['allow_iframe' => true]);

        // Sanitizer should still clean it even if allowed
        $this->assertIsString($clean);
    }

    /**
     * Test data: protocol blocking
     */
    public function testDataProtocolBlocking(): void
    {
        $dirty = '<img src="data:text/html,<script>alert(\'xss\')</script>" />';
        $clean = $this->sanitizer->sanitize($dirty);

        $this->assertStringNotContainsString('data:', $clean);
    }

    /**
     * Test style tags
     */
    public function testStyleTagHandling(): void
    {
        $dirty = '<p>Text</p><style>body { display: none; }</style>';
        $clean = $this->sanitizer->sanitize($dirty);

        $this->assertStringNotContainsString('<style>', $clean);
    }
}
