<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\SeoService;

class SeoServiceTest extends TestCase
{
    private SeoService $seoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seoService = new SeoService();
    }

    /**
     * Test buildHreflangPayload
     */
    public function testBuildHreflangPayload(): void
    {
        $post = [
            'id' => 1,
            'slug' => 'test-post',
            'translations' => [
                ['language' => 'en'],
                ['language' => 'pt'],
                ['language' => 'es'],
            ]
        ];

        $hreflang = $this->seoService->buildHreflangPayload($post, 'en');

        $this->assertIsArray($hreflang);
        $this->assertNotEmpty($hreflang);
    }

    /**
     * Test buildCanonicalUrl
     */
    public function testBuildCanonicalUrl(): void
    {
        $baseUrl = 'https://example.com';
        $slug = 'test-post';
        $language = 'en';

        $canonical = $this->seoService->buildCanonicalUrl($baseUrl, $slug, $language);

        $this->assertStringContainsString($baseUrl, $canonical);
        $this->assertStringContainsString($slug, $canonical);
    }

    /**
     * Test buildOpenGraph
     */
    public function testBuildOpenGraph(): void
    {
        $post = [
            'title' => 'Test Post',
            'excerpt' => 'This is a test post',
            'featured_image' => '/images/test.jpg',
            'slug' => 'test-post'
        ];

        $og = $this->seoService->buildOpenGraph($post, 'https://example.com');

        $this->assertArrayHasKey('og:title', $og);
        $this->assertArrayHasKey('og:description', $og);
        $this->assertArrayHasKey('og:image', $og);
        $this->assertArrayHasKey('og:url', $og);
        $this->assertEquals('Test Post', $og['og:title']);
    }

    /**
     * Test invalid URL handling
     */
    public function testInvalidUrlHandling(): void
    {
        $canonical = $this->seoService->buildCanonicalUrl('invalid-url', 'test', 'en');
        $this->assertIsString($canonical);
    }
}
