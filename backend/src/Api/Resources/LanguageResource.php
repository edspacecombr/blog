<?php

namespace App\Api\Resources;

class LanguageResource extends BaseResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->data['id'],
            'code' => $this->data['code'],
            'name' => $this->data['name'],
            'native_name' => $this->data['native_name'] ?? $this->data['name'],
            'locale' => $this->data['locale'] ?? $this->data['code'],
            'direction' => $this->data['direction'] ?? 'ltr',
            'is_enabled' => (bool)$this->data['is_enabled'],
            'is_primary' => (bool)$this->data['is_primary'],
            'created_at' => $this->data['created_at'],
            'updated_at' => $this->data['updated_at'],
        ];
    }
}
