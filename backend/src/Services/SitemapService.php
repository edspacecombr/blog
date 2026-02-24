<?php

namespace App\Services;

use App\Database\Connection;
use PDO;

class SitemapService
{
    private PDO $db;
    private string $siteUrl;
    private string $publicPath;
    private SeoService $seoService;

    public function __construct(
        string $siteUrl = 'http://localhost:8000',
        string $publicPath = __DIR__ . '/../../public'
    )
    {
        $this->db = Connection::getInstance();
        $this->siteUrl = rtrim($siteUrl, '/');
        $this->publicPath = $publicPath;
        $this->seoService = new SeoService($siteUrl);
    }

    /**
     * Generate complete sitemap index
     */
    public function generateSitemapIndex(): string
    {
        // Get all active languages
        $query = "SELECT code FROM languages WHERE is_enabled = true ORDER BY is_primary DESC";
        $stmt = $this->db->query($query);
        $languages = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($languages as $lang) {
            $xml .= "  <sitemap>\n";
            $xml .= "    <loc>" . htmlspecialchars("{$this->siteUrl}/sitemap-{$lang}.xml") . "</loc>\n";
            $xml .= "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
            $xml .= "  </sitemap>\n";
        }

        // Add main pages
        $xml .= "  <sitemap>\n";
        $xml .= "    <loc>" . htmlspecialchars("{$this->siteUrl}/sitemap-pages.xml") . "</loc>\n";
        $xml .= "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
        $xml .= "  </sitemap>\n";

        $xml .= "</sitemapindex>\n";

        return $xml;
    }

    /**
     * Generate sitemap for a specific language
     */
    public function generateLanguageSitemap(string $languageCode): string
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        // Get published posts for language
        $query = "
            SELECT id, slug, updated_at, views_count
            FROM posts 
            WHERE language = ? AND status = 'published'
            ORDER BY updated_at DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$languageCode]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($posts as $post) {
            $url = "{$this->siteUrl}/{$languageCode}/{$post['slug']}";
            $lastmod = date('Y-m-d', strtotime($post['updated_at']));
            $priority = $this->calculatePriority($post['views_count'] ?? 0);
            
            $xml .= $this->seoService->buildSitemapEntry($url, $lastmod, 'weekly', $priority);
        }

        $xml .= "</urlset>\n";

        return $xml;
    }

    /**
     * Generate sitemap for static pages
     */
    public function generatePagesSitemap(): string
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        // Get published pages for all languages
        $query = "
            SELECT DISTINCT language, slug, updated_at
            FROM pages 
            WHERE status = 'published'
            ORDER BY updated_at DESC
        ";
        $stmt = $this->db->query($query);
        $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pages as $page) {
            $url = "{$this->siteUrl}/{$page['language']}/{$page['slug']}";
            $lastmod = date('Y-m-d', strtotime($page['updated_at']));
            
            $xml .= $this->seoService->buildSitemapEntry($url, $lastmod, 'monthly', '0.7');
        }

        // Add category pages
        $query = "
            SELECT DISTINCT language, slug
            FROM categories 
            ORDER BY slug
        ";
        $stmt = $this->db->query($query);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categories as $cat) {
            $url = "{$this->siteUrl}/{$cat['language']}/category/{$cat['slug']}";
            $xml .= $this->seoService->buildSitemapEntry($url, date('Y-m-d'), 'weekly', '0.6');
        }

        $xml .= "</urlset>\n";

        return $xml;
    }

    /**
     * Save sitemap to file
     */
    public function saveSitemap(string $filename, string $content): bool
    {
        $filepath = "{$this->publicPath}/{$filename}";
        return file_put_contents($filepath, $content) !== false;
    }

    /**
     * Generate and save all sitemaps
     */
    public function generateAll(): bool
    {
        try {
            // Generate and save main index
            $index = $this->generateSitemapIndex();
            $this->saveSitemap('sitemap.xml', $index);

            // Generate and save language-specific sitemaps
            $query = "SELECT code FROM languages WHERE is_enabled = true";
            $stmt = $this->db->query($query);
            $languages = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($languages as $lang) {
                $langSitemap = $this->generateLanguageSitemap($lang);
                $this->saveSitemap("sitemap-{$lang}.xml", $langSitemap);
            }

            // Generate and save pages sitemap
            $pagesSitemap = $this->generatePagesSitemap();
            $this->saveSitemap('sitemap-pages.xml', $pagesSitemap);

            return true;
        } catch (\Exception $e) {
            error_log("Sitemap generation failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Calculate priority based on views
     */
    private function calculatePriority(int $views): string
    {
        if ($views > 1000) {
            return '0.9';
        } elseif ($views > 500) {
            return '0.8';
        } elseif ($views > 100) {
            return '0.7';
        } elseif ($views > 50) {
            return '0.6';
        }
        return '0.5';
    }

    /**
     * Check if sitemap is up to date
     */
    public function isSitemapUpToDate(string $filename, int $maxAgeHours = 24): bool
    {
        $filepath = "{$this->publicPath}/{$filename}";
        
        if (!file_exists($filepath)) {
            return false;
        }

        $fileAge = time() - filemtime($filepath);
        $maxAgeSeconds = $maxAgeHours * 3600;

        return $fileAge < $maxAgeSeconds;
    }
}
