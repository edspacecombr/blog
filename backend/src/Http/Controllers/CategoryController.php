<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Api\Resources\CategoryResource;

class CategoryController extends BaseController
{
    private Category $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    public function index(): void
    {
        $page = (int)$this->getQueryParam('page', 1);
        $perPage = (int)$this->getQueryParam('per_page', 15);
        $language = $this->getQueryParam('language', 'en');

        $filters = ['language' => $language];
        $result = $this->categoryModel->paginate($page, $perPage, $filters);
        
        $data = array_map(fn($item) => (new CategoryResource($item))->toArray(), $result['data']);
        $response = ['data' => $data, 'pagination' => $result['pagination']];

        $this->success($response);
    }

    public function show($id): void
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            $this->error('Category not found', 404);
        }
        $this->success(new CategoryResource($category));
    }

    public function store(): void
    {
        $data = $this->getJsonBody();
        $this->validateRequired($data, ['name', 'language']);

        $categoryData = [
            'name' => $data['name'],
            'slug' => $this->generateSlug($data['name']),
            'description' => $data['description'] ?? null,
            'language' => $data['language'] ?? 'en',
            'parent_id' => $data['parent_id'] ?? null,
            'display_order' => $data['display_order'] ?? 0,
            'color' => $data['color'] ?? null,
            'icon' => $data['icon'] ?? null,
        ];

        $category = $this->categoryModel->create($categoryData);
        if (!$category) {
            $this->error('Failed to create category', 500);
        }
        $this->success(new CategoryResource($category), 'Category created successfully', 201);
    }

    public function update($id): void
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            $this->error('Category not found', 404);
        }

        $data = $this->getJsonBody();
        $categoryData = [];
        $allowedFields = ['name', 'description', 'parent_id', 'display_order', 'color', 'icon'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                if ($field === 'name' && $data[$field] !== $category['name']) {
                    $categoryData['slug'] = $this->generateSlug($data[$field]);
                }
                $categoryData[$field] = $data[$field];
            }
        }

        if (empty($categoryData)) {
            $this->error('No fields to update', 422);
        }

        $updated = $this->categoryModel->update($id, $categoryData);
        if (!$updated) {
            $this->error('Failed to update category', 500);
        }
        $this->success(new CategoryResource($updated), 'Category updated successfully');
    }

    public function destroy($id): void
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            $this->error('Category not found', 404);
        }

        $deleted = $this->categoryModel->delete($id);
        if (!$deleted) {
            $this->error('Failed to delete category', 500);
        }
        $this->success(null, 'Category deleted successfully');
    }

    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        return trim($slug, '-');
    }
}
