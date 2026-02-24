<?php

namespace App\Api\Resources;

class AuthorResource extends BaseResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->data['id'] ?? null,
            'name' => $this->data['name'] ?? null,
            'email' => $this->data['email'] ?? null,
            'bio' => $this->data['bio'] ?? [],
            'avatar_path' => $this->data['avatar_path'] ?? null,
            'social_links' => $this->data['social_links'] ?? [],
            'schema_same_as' => $this->data['schema_same_as'] ?? null,
            'created_at' => $this->formatTimestamp($this->data['created_at'] ?? null),
            'updated_at' => $this->formatTimestamp($this->data['updated_at'] ?? null),
        ];
    }
}
