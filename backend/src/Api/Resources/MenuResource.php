<?php

namespace App\Api\Resources;

class MenuResource extends BaseResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->data['id'] ?? null,
            'name' => $this->data['name'] ?? null,
            'slug' => $this->data['slug'] ?? null,
            'created_at' => $this->formatTimestamp($this->data['created_at'] ?? null),
            'updated_at' => $this->formatTimestamp($this->data['updated_at'] ?? null),
        ];
    }
}
