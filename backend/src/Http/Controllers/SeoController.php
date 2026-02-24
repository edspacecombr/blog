<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use App\Services\SeoService;
use App\Models\BlogSettings;

class SeoController extends BaseController
{
    private SitemapService $sitemapService;
    private SeoService $seoService;
    private BlogSettings $settingsModel;

    public function __construct()
    {
        $this->sitemapService = new SitemapService(
            getenv('APP_URL') ?: 'http://localhost:8000',
            __DIR__ . '/../../public'
        );
        $this->seoService = new SeoService(
            getenv('APP_URL') ?: 'http://localhost:8000'
        );
        $this->settingsModel = new BlogSettings();
    }

    /**
     * GET /api/v1/sitemap.xml
     * Serve main sitemap
     */
    public function sitemap(): void
    {
        // Check if sitemap exists and is recent, otherwise regenerate
        if (!$this->sitemapService->isSitemapUpToDate('sitemap.xml', 24)) {
            $this->sitemapService->generateAll();
        }

        $filepath = __DIR__ . '/../../public/sitemap.xml';
        
        if (!file_exists($filepath)) {
            $this->error('Sitemap not found', 404);
            return;
        }

        header('Content-Type: application/xml; charset=utf-8');
        echo file_get_contents($filepath);
        exit;
    }

    /**
     * GET /api/v1/sitemap-{lang}.xml
     * Serve language-specific sitemap
     */
    public function sitemapLanguage($lang): void
    {
        // Sanitize language code
        $lang = preg_replace('/[^a-z0-9-]/', '', strtolower($lang));
        
        if (!$this->sitemapService->isSitemapUpToDate("sitemap-{$lang}.xml", 24)) {
            $this->sitemapService->generateAll();
        }

        $filepath = __DIR__ . "/../../public/sitemap-{$lang}.xml";
        
        if (!file_exists($filepath)) {
            $this->error('Sitemap not found', 404);
            return;
        }

        header('Content-Type: application/xml; charset=utf-8');
        echo file_get_contents($filepath);
        exit;
    }

    /**
     * GET /robots.txt
     * Serve robots.txt
     */
    public function robots(): void
    {
        $settings = $this->settingsModel->all();
        $robotsContent = $settings['robots_txt_content'] ?? '';

        if (empty($robotsContent)) {
            // Generate default robots.txt
            $sitemapUrl = (getenv('APP_URL') ?: 'http://localhost:8000') . '/sitemap.xml';
            $robotsContent = $this->seoService->buildRobotsTxt(
                disallowPaths: ['/admin/', '/api/'],
                allowPaths: ['/api/v1/public/'],
                sitemapUrl: $sitemapUrl
            );
        }

        header('Content-Type: text/plain; charset=utf-8');
        echo $robotsContent;
        exit;
    }

    /**
     * POST /api/v1/settings
     * Get global settings
     */
    public function getSettings(): void
    {
        $settings = $this->settingsModel->all();
        
        $data = [
            'seo' => [
                'title_pattern' => $settings['title_pattern'] ?? '{title} | {site_name}',
                'site_name' => $settings['site_name'] ?? 'My Blog',
                'default_og_image_id' => $settings['default_og_image_id'] ?? null,
                'robots_txt_content' => $settings['robots_txt_content'] ?? '',
            ],
            'general' => [
                'site_name' => $settings['site_name'] ?? 'My Blog',
                'description' => $settings['description'] ?? '',
                'tagline' => $settings['tagline'] ?? '',
            ],
        ];

        $this->success(['data' => $data]);
    }

    /**
     * PATCH /api/v1/settings
     * Update global settings
     */
    public function updateSettings(): void
    {
        $data = $this->getJsonBody();
        
        $updateData = [];
        
        if (isset($data['seo']['title_pattern'])) {
            $updateData['title_pattern'] = $data['seo']['title_pattern'];
        }
        if (isset($data['seo']['site_name'])) {
            $updateData['site_name'] = $data['seo']['site_name'];
        }
        if (isset($data['seo']['default_og_image_id'])) {
            $updateData['default_og_image_id'] = $data['seo']['default_og_image_id'];
        }
        if (isset($data['seo']['robots_txt_content'])) {
            $updateData['robots_txt_content'] = $data['seo']['robots_txt_content'];
        }

        if (empty($updateData)) {
            $this->error('No fields to update', 422);
            return;
        }

        foreach ($updateData as $key => $value) {
            $this->settingsModel->db->prepare("UPDATE blog_settings SET $key = ? WHERE id = 1")
                ->execute([$value]);
        }

        // Regenerate sitemaps if robots.txt was updated
        if (isset($updateData['robots_txt_content'])) {
            $this->sitemapService->generateAll();
        }

        $this->success(['data' => $updateData], 'Settings updated successfully');
    }

    /**
     * POST /api/v1/sitemaps/regenerate
     * Force regenerate all sitemaps
     */
    public function regenerateSitemaps(): void
    {
        if (!$this->sitemapService->generateAll()) {
            $this->error('Failed to regenerate sitemaps', 500);
            return;
        }

        $this->success(['message' => 'Sitemaps regenerated successfully'], 'OK', 200);
    }
}
