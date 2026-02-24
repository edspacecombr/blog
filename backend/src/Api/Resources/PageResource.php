<?php

namespace App\Api\Resources;

class PageResource extends BaseResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->data['id'] ?? null,
            'title' => $this->data['title'] ?? null,
            'slug' => $this->data['slug'] ?? null,
            'content' => $this->data['content'] ?? null,
            'language' => $this->data['language'] ?? 'en',
            'status' => $this->data['status'] ?? 'draft',
            'seo' => [
                'title' => $this->data['seo_title'] ?? null,
                'description' => $this->data['seo_description'] ?? null,
            ],
            'created_at' => $this->formatTimestamp($this->data['created_at'] ?? null),
            'updated_at' => $this->formatTimestamp($this->data['updated_at'] ?? null),
        ];
    }
}
