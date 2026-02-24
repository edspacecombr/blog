<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Api\Resources\LanguageResource;

class LanguageController extends BaseController
{
    private Language $languageModel;

    public function __construct()
    {
        $this->languageModel = new Language();
    }

    /**
     * GET /api/v1/languages
     * List all languages
     */
    public function index(): void
    {
        $languages = $this->languageModel->all();
        
        $data = array_map(fn($lang) => (new LanguageResource($lang))->toArray(), $languages);
        $this->success(['data' => $data]);
    }

    /**
     * GET /api/v1/languages/{id}
     * Get single language
     */
    public function show($id): void
    {
        $language = $this->languageModel->find($id);
        
        if (!$language) {
            $this->error('Language not found', 404);
            return;
        }

        $this->success(new LanguageResource($language));
    }

    /**
     * POST /api/v1/languages
     * Create new language
     */
    public function store(): void
    {
        $data = $this->getJsonBody();
        
        $this->validateRequired($data, ['code', 'name']);

        $languageData = [
            'code' => $data['code'],
            'name' => $data['name'],
            'native_name' => $data['native_name'] ?? $data['name'],
            'direction' => $data['direction'] ?? 'ltr',
            'locale' => $data['locale'] ?? $data['code'],
            'is_enabled' => $data['is_enabled'] ?? true,
            'is_primary' => $data['is_primary'] ?? false,
        ];

        // If setting as primary, unset other primaries
        if ($languageData['is_primary']) {
            $this->languageModel->db->exec("UPDATE languages SET is_primary = false WHERE is_primary = true");
        }

        $language = $this->languageModel->create($languageData);
        
        if (!$language) {
            $this->error('Failed to create language', 500);
            return;
        }

        $this->success(new LanguageResource($language), 'Language created successfully', 201);
    }

    /**
     * PATCH /api/v1/languages/{id}
     * Update language
     */
    public function update($id): void
    {
        $language = $this->languageModel->find($id);
        
        if (!$language) {
            $this->error('Language not found', 404);
            return;
        }

        $data = $this->getJsonBody();
        
        $languageData = [];
        $allowedFields = ['name', 'native_name', 'direction', 'locale', 'is_enabled', 'is_primary'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $languageData[$field] = $data[$field];
            }
        }

        // If setting as primary, unset other primaries
        if (isset($languageData['is_primary']) && $languageData['is_primary']) {
            $this->languageModel->db->exec("UPDATE languages SET is_primary = false WHERE id != ?", [$id]);
        }

        if (empty($languageData)) {
            $this->error('No fields to update', 422);
            return;
        }

        $updated = $this->languageModel->update($id, $languageData);
        
        if (!$updated) {
            $this->error('Failed to update language', 500);
            return;
        }

        $this->success(new LanguageResource($updated), 'Language updated successfully');
    }

    /**
     * DELETE /api/v1/languages/{id}
     * Delete language
     */
    public function destroy($id): void
    {
        $language = $this->languageModel->find($id);
        
        if (!$language) {
            $this->error('Language not found', 404);
            return;
        }

        // Prevent deleting primary language
        if ($language['is_primary']) {
            $this->error('Cannot delete primary language', 422);
            return;
        }

        $deleted = $this->languageModel->delete($id);
        
        if (!$deleted) {
            $this->error('Failed to delete language', 500);
            return;
        }

        $this->success(null, 'Language deleted successfully', 204);
    }

    /**
     * Generate slug from name
     */
    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
