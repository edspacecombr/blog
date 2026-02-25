<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\SchemaService;

class SchemaServiceTest extends TestCase
{
    private SchemaService $schemaService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->schemaService = new SchemaService();
    }

    /**
     * Test buildArticleSchema
     */
    public function testBuildArticleSchema(): void
    {
        $post = [
            'id' => 1,
            'title' => 'Test Article',
            'excerpt' => 'Article excerpt',
            'body' => 'Article body content',
            'published_at' => '2024-02-24T00:00:00Z',
            'updated_at' => '2024-02-24T12:00:00Z',
            'featured_image' => '/images/test.jpg',
            'author' => [
                'id' => 1,
                'name' => 'Test Author'
            ],
            'category' => [
                'id' => 1,
                'name' => 'Test Category'
            ]
        ];

        $schema = $this->schemaService->buildArticleSchema($post, 'https://example.com');

        $this->assertIsArray($schema);
        $this->assertEquals('NewsArticle', $schema['@type']);
        $this->assertEquals('Test Article', $schema['headline']);
        $this->assertArrayHasKey('author', $schema);
        $this->assertArrayHasKey('datePublished', $schema);
        $this->assertArrayHasKey('dateModified', $schema);
    }

    /**
     * Test buildBreadcrumbSchema
     */
    public function testBuildBreadcrumbSchema(): void
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => 'https://example.com'],
            ['name' => 'Blog', 'url' => 'https://example.com/blog'],
            ['name' => 'Test Post', 'url' => 'https://example.com/blog/test-post']
        ];

        $schema = $this->schemaService->buildBreadcrumbSchema($breadcrumbs);

        $this->assertIsArray($schema);
        $this->assertEquals('BreadcrumbList', $schema['@type']);
        $this->assertCount(3, $schema['itemListElement']);
    }

    /**
     * Test buildAuthorSchema
     */
    public function testBuildAuthorSchema(): void
    {
        $author = [
            'id' => 1,
            'name' => 'Test Author',
            'avatar' => '/images/author.jpg',
            'bio' => 'Author biography'
        ];

        $schema = $this->schemaService->buildAuthorSchema($author);

        $this->assertIsArray($schema);
        $this->assertEquals('Person', $schema['@type']);
        $this->assertEquals('Test Author', $schema['name']);
        $this->assertArrayHasKey('image', $schema);
        $this->assertArrayHasKey('description', $schema);
    }

    /**
     * Test buildOrgSchema
     */
    public function testBuildOrgSchema(): void
    {
        $siteSettings = [
            'name' => 'Test Blog',
            'url' => 'https://example.com',
            'logo' => '/logo.png',
            'description' => 'Test blog description'
        ];

        $schema = $this->schemaService->buildOrgSchema($siteSettings);

        $this->assertIsArray($schema);
        $this->assertEquals('Organization', $schema['@type']);
        $this->assertEquals('Test Blog', $schema['name']);
        $this->assertArrayHasKey('url', $schema);
        $this->assertArrayHasKey('logo', $schema);
    }
}
