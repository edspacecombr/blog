<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\MediaService;

class MediaServiceTest extends TestCase
{
    private MediaService $mediaService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mediaService = new MediaService();
    }

    /**
     * Test MIME type validation for images
     */
    public function testValidImageMimeTypes(): void
    {
        $validMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        foreach ($validMimes as $mime) {
            $result = $this->mediaService->isValidImageMime($mime);
            $this->assertTrue($result, "MIME type $mime should be valid");
        }
    }

    /**
     * Test invalid MIME type rejection
     */
    public function testInvalidMimeTypes(): void
    {
        $invalidMimes = ['application/pdf', 'video/mp4', 'text/html', 'application/json'];

        foreach ($invalidMimes as $mime) {
            $result = $this->mediaService->isValidImageMime($mime);
            $this->assertFalse($result, "MIME type $mime should be invalid");
        }
    }

    /**
     * Test file size validation
     */
    public function testFileSizeValidation(): void
    {
        // 10MB max
        $maxSize = 10 * 1024 * 1024;

        $result = $this->mediaService->isValidFileSize(5 * 1024 * 1024, $maxSize);
        $this->assertTrue($result, "5MB file should be valid");

        $result = $this->mediaService->isValidFileSize(15 * 1024 * 1024, $maxSize);
        $this->assertFalse($result, "15MB file should be invalid");
    }

    /**
     * Test safe filename generation
     */
    public function testSafeFilenameGeneration(): void
    {
        $unsafe = "test file@#$%.jpg";
        $safe = $this->mediaService->generateSafeFilename($unsafe);

        $this->assertStringNotContainsString('@', $safe);
        $this->assertStringNotContainsString('#', $safe);
        $this->assertStringNotContainsString('$', $safe);
        $this->assertStringNotContainsString('%', $safe);
        $this->assertStringEndsWith('.jpg', $safe);
    }

    /**
     * Test WebP format support
     */
    public function testWebPFormatSupport(): void
    {
        $result = $this->mediaService->supportsFormat('webp');
        $this->assertTrue($result);
    }

    /**
     * Test AVIF format support
     */
    public function testAvifFormatSupport(): void
    {
        $result = $this->mediaService->supportsFormat('avif');
        $this->assertTrue($result);
    }
}
