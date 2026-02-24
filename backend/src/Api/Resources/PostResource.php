<?php

namespace App\Api\Resources;

class PostResource extends BaseResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->data['id'] ?? null,
            'title' => $this->data['title'] ?? null,
            'slug' => $this->data['slug'] ?? null,
            'content' => $this->data['content'] ?? null,
            'excerpt' => $this->data['excerpt'] ?? null,
            'status' => $this->data['status'] ?? 'draft',
            'language' => $this->data['language'] ?? 'en',
            'author_id' => $this->data['author_id'] ?? null,
            'featured_image_id' => $this->data['featured_image_id'] ?? null,
            'views_count' => (int)($this->data['views_count'] ?? 0),
            'seo' => [
                'title' => $this->data['seo_title'] ?? null,
                'description' => $this->data['seo_description'] ?? null,
                'keywords' => $this->data['seo_keywords'] ?? null,
                'og_title' => $this->data['meta_og_title'] ?? null,
                'og_description' => $this->data['meta_og_description'] ?? null,
                'og_image_id' => $this->data['meta_og_image_id'] ?? null,
            ],
            'scheduled_at' => $this->formatTimestamp($this->data['scheduled_at'] ?? null),
            'published_at' => $this->formatTimestamp($this->data['published_at'] ?? null),
            'created_at' => $this->formatTimestamp($this->data['created_at'] ?? null),
            'updated_at' => $this->formatTimestamp($this->data['updated_at'] ?? null),
        ];
    }
}
