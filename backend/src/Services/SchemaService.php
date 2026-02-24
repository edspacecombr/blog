<?php

namespace App\Services;

class SchemaService
{
    private string $siteUrl;
    private array $siteInfo;

    public function __construct(string $siteUrl = 'http://localhost:8000', array $siteInfo = [])
    {
        $this->siteUrl = rtrim($siteUrl, '/');
        $this->siteInfo = $siteInfo;
    }

    /**
     * Build Article schema (JSON-LD)
     */
    public function buildArticleSchema(
        string $headline,
        string $description,
        string $articleBody,
        string $imageUrl,
        string $datePublished,
        string $dateModified,
        array $author,
        string $url = ''
    ): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $headline,
            'description' => $description,
            'image' => [
                '@type' => 'ImageObject',
                'url' => $imageUrl,
                'width' => 1200,
                'height' => 630,
            ],
            'datePublished' => date('c', strtotime($datePublished)),
            'dateModified' => date('c', strtotime($dateModified)),
            'author' => [
                '@type' => 'Person',
                'name' => $author['name'] ?? 'Unknown',
                'url' => $author['url'] ?? '',
            ],
            'articleBody' => strip_tags($articleBody),
            'url' => $url ?: $this->siteUrl,
        ];
    }

    /**
     * Build Breadcrumb schema (JSON-LD)
     */
    public function buildBreadcrumbSchema(array $breadcrumbs): array
    {
        $items = [];
        
        foreach ($breadcrumbs as $index => $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['name'],
                'item' => $crumb['item'] ?? '',
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Build Author schema (JSON-LD)
     */
    public function buildAuthorSchema(
        string $name,
        string $bio = '',
        string $imageUrl = '',
        string $url = '',
        array $socialProfiles = []
    ): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $name,
            'url' => $url ?: $this->siteUrl,
        ];

        if ($bio) {
            $schema['description'] = $bio;
        }

        if ($imageUrl) {
            $schema['image'] = $imageUrl;
        }

        if (!empty($socialProfiles)) {
            $schema['sameAs'] = $socialProfiles;
        }

        return $schema;
    }

    /**
     * Build Organization schema (JSON-LD)
     */
    public function buildOrgSchema(
        string $name = '',
        string $description = '',
        string $imageUrl = '',
        string $logo = '',
        string $url = '',
        array $socialProfiles = [],
        string $email = ''
    ): array
    {
        $orgName = $name ?: ($this->siteInfo['site_name'] ?? 'Blog');
        $orgUrl = $url ?: $this->siteUrl;

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $orgName,
            'url' => $orgUrl,
            'description' => $description ?: ($this->siteInfo['description'] ?? ''),
            'image' => $imageUrl ?: ($this->siteInfo['logo'] ?? ''),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo ?: ($this->siteInfo['logo'] ?? ''),
                'width' => 250,
                'height' => 60,
            ],
            'sameAs' => $socialProfiles,
            'email' => $email ?: ($this->siteInfo['email'] ?? ''),
        ];
    }

    /**
     * Build WebSite schema (JSON-LD)
     */
    public function buildWebSiteSchema(
        string $name = '',
        string $description = '',
        string $imageUrl = '',
        string $url = ''
    ): array
    {
        $siteName = $name ?: ($this->siteInfo['site_name'] ?? 'Blog');
        $siteUrl = $url ?: $this->siteUrl;

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => $siteUrl,
            'description' => $description ?: ($this->siteInfo['description'] ?? ''),
            'image' => $imageUrl ?: ($this->siteInfo['logo'] ?? ''),
            'inLanguage' => $this->siteInfo['language'] ?? 'en',
        ];
    }

    /**
     * Build FAQPage schema (JSON-LD)
     */
    public function buildFAQSchema(array $faqs): array
    {
        $mainEntity = [];
        
        foreach ($faqs as $faq) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $faq['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'] ?? '',
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity,
        ];
    }

    /**
     * Convert schema array to JSON-LD script tag
     */
    public function toJsonLdScriptTag(array $schema): string
    {
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
}
