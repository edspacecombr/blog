<?php

namespace App\Services;

class SeoService
{
    private string $siteUrl;
    private string $defaultLanguage;

    public function __construct(string $siteUrl = 'http://localhost:8000', string $defaultLanguage = 'en')
    {
        $this->siteUrl = rtrim($siteUrl, '/');
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * Build hreflang payload for multi-language support
     */
    public function buildHreflangPayload(string $slug, array $languages, string $currentLanguage = 'en'): array
    {
        $hreflangs = [];
        
        foreach ($languages as $lang) {
            $code = $lang['code'] ?? $lang;
            $hreflangs[] = [
                'rel' => 'alternate',
                'hreflang' => $code,
                'href' => "{$this->siteUrl}/{$code}/{$slug}",
            ];
        }

        // Add x-default
        $hreflangs[] = [
            'rel' => 'alternate',
            'hreflang' => 'x-default',
            'href' => "{$this->siteUrl}/{$this->defaultLanguage}/{$slug}",
        ];

        return $hreflangs;
    }

    /**
     * Build canonical URL
     */
    public function buildCanonicalUrl(string $slug, string $language = 'en'): string
    {
        return "{$this->siteUrl}/{$language}/{$slug}";
    }

    /**
     * Build Open Graph meta tags
     */
    public function buildOpenGraph(
        string $title,
        string $description,
        string $imageUrl = '',
        string $url = '',
        string $type = 'article'
    ): array
    {
        return [
            'og:title' => $title,
            'og:description' => $description,
            'og:type' => $type,
            'og:url' => $url ?: $this->siteUrl,
            'og:image' => $imageUrl,
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $title,
            'twitter:description' => $description,
            'twitter:image' => $imageUrl,
        ];
    }

    /**
     * Build breadcrumb list for SEO
     */
    public function buildBreadcrumb(string $language, string $title, string $slug): array
    {
        return [
            [
                'name' => 'Home',
                'item' => "{$this->siteUrl}/{$language}",
            ],
            [
                'name' => $title,
                'item' => "{$this->siteUrl}/{$language}/{$slug}",
            ],
        ];
    }

    /**
     * Generate sitemap URL entry
     */
    public function buildSitemapEntry(
        string $url,
        string $lastmod = '',
        string $changefreq = 'weekly',
        string $priority = '0.8'
    ): string
    {
        $entry = "  <url>\n";
        $entry .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
        
        if ($lastmod) {
            $entry .= "    <lastmod>$lastmod</lastmod>\n";
        }
        
        $entry .= "    <changefreq>$changefreq</changefreq>\n";
        $entry .= "    <priority>$priority</priority>\n";
        $entry .= "  </url>\n";
        
        return $entry;
    }

    /**
     * Build robots.txt content
     */
    public function buildRobotsTxt(
        array $disallowPaths = [],
        array $allowPaths = [],
        string $sitemapUrl = ''
    ): string
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";

        if (!empty($disallowPaths)) {
            foreach ($disallowPaths as $path) {
                $robots .= "Disallow: $path\n";
            }
        }

        if (!empty($allowPaths)) {
            foreach ($allowPaths as $path) {
                $robots .= "Allow: $path\n";
            }
        }

        if ($sitemapUrl) {
            $robots .= "\nSitemap: $sitemapUrl\n";
        }

        return $robots;
    }

    /**
     * Build meta tags object
     */
    public function buildMetaTags(
        string $title,
        string $description,
        string $keywords = '',
        array $languages = [],
        string $currentLanguage = 'en',
        string $slug = '',
        string $imageUrl = ''
    ): array
    {
        $url = $slug ? $this->buildCanonicalUrl($slug, $currentLanguage) : '';

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'canonical' => $url,
            'alternates' => [
                'languages' => $this->buildHreflangPayload($slug, $languages, $currentLanguage),
            ],
            'openGraph' => $this->buildOpenGraph($title, $description, $imageUrl, $url),
        ];
    }
}
