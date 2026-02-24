<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Api\Resources\PageResource;

class PageController extends BaseController
{
    private Page $pageModel;

    public function __construct()
    {
        $this->pageModel = new Page();
    }

    public function index(): void
    {
        $page = (int)$this->getQueryParam('page', 1);
        $perPage = (int)$this->getQueryParam('per_page', 15);
        $language = $this->getQueryParam('language', 'en');

        $filters = ['language' => $language];
        $result = $this->pageModel->paginate($page, $perPage, $filters);
        
        $data = array_map(fn($item) => (new PageResource($item))->toArray(), $result['data']);
        $response = ['data' => $data, 'pagination' => $result['pagination']];

        $this->success($response);
    }

    public function show($id): void
    {
        $page = $this->pageModel->find($id);
        if (!$page) {
            $this->error('Page not found', 404);
        }
        $this->success(new PageResource($page));
    }

    public function store(): void
    {
        $data = $this->getJsonBody();
        $this->validateRequired($data, ['title', 'content', 'language']);

        $pageData = [
            'title' => $data['title'],
            'slug' => $this->generateSlug($data['title']),
            'content' => $data['content'],
            'language' => $data['language'] ?? 'en',
            'status' => $data['status'] ?? 'draft',
            'seo_title' => $data['seo_title'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
        ];

        $page = $this->pageModel->create($pageData);
        if (!$page) {
            $this->error('Failed to create page', 500);
        }
        $this->success(new PageResource($page), 'Page created successfully', 201);
    }

    public function update($id): void
    {
        $page = $this->pageModel->find($id);
        if (!$page) {
            $this->error('Page not found', 404);
        }

        $data = $this->getJsonBody();
        $pageData = [];
        $allowedFields = ['title', 'content', 'status', 'seo_title', 'seo_description'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                if ($field === 'title' && $data[$field] !== $page['title']) {
                    $pageData['slug'] = $this->generateSlug($data[$field]);
                }
                $pageData[$field] = $data[$field];
            }
        }

        if (empty($pageData)) {
            $this->error('No fields to update', 422);
        }

        $updated = $this->pageModel->update($id, $pageData);
        if (!$updated) {
            $this->error('Failed to update page', 500);
        }
        $this->success(new PageResource($updated), 'Page updated successfully');
    }

    public function destroy($id): void
    {
        $page = $this->pageModel->find($id);
        if (!$page) {
            $this->error('Page not found', 404);
        }

        $deleted = $this->pageModel->delete($id);
        if (!$deleted) {
            $this->error('Failed to delete page', 500);
        }
        $this->success(null, 'Page deleted successfully');
    }

    private function generateSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        return trim($slug, '-');
    }
}
