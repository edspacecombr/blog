<?php

namespace App\Api\Resources;

class CategoryResource extends BaseResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->data['id'] ?? null,
            'name' => $this->data['name'] ?? null,
            'slug' => $this->data['slug'] ?? null,
            'description' => $this->data['description'] ?? null,
            'language' => $this->data['language'] ?? 'en',
            'parent_id' => $this->data['parent_id'] ?? null,
            'display_order' => (int)($this->data['display_order'] ?? 0),
            'color' => $this->data['color'] ?? null,
            'icon' => $this->data['icon'] ?? null,
            'created_at' => $this->formatTimestamp($this->data['created_at'] ?? null),
            'updated_at' => $this->formatTimestamp($this->data['updated_at'] ?? null),
        ];
    }
}
