<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Api\Resources\MenuResource;

class MenuController extends BaseController
{
    private Menu $menuModel;

    public function __construct()
    {
        $this->menuModel = new Menu();
    }

    public function index(): void
    {
        $page = (int)$this->getQueryParam('page', 1);
        $perPage = (int)$this->getQueryParam('per_page', 15);

        $result = $this->menuModel->paginate($page, $perPage);
        
        $data = array_map(fn($item) => (new MenuResource($item))->toArray(), $result['data']);
        $response = ['data' => $data, 'pagination' => $result['pagination']];

        $this->success($response);
    }

    public function show($id): void
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            $this->error('Menu not found', 404);
        }
        $this->success(new MenuResource($menu));
    }

    public function store(): void
    {
        $data = $this->getJsonBody();
        $this->validateRequired($data, ['name']);

        $menuData = [
            'name' => $data['name'],
            'slug' => $this->generateSlug($data['name']),
        ];

        $menu = $this->menuModel->create($menuData);
        if (!$menu) {
            $this->error('Failed to create menu', 500);
        }
        $this->success(new MenuResource($menu), 'Menu created successfully', 201);
    }

    public function update($id): void
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            $this->error('Menu not found', 404);
        }

        $data = $this->getJsonBody();
        $menuData = [];
        
        if (isset($data['name'])) {
            $menuData['name'] = $data['name'];
            $menuData['slug'] = $this->generateSlug($data['name']);
        }

        if (empty($menuData)) {
            $this->error('No fields to update', 422);
        }

        $updated = $this->menuModel->update($id, $menuData);
        if (!$updated) {
            $this->error('Failed to update menu', 500);
        }
        $this->success(new MenuResource($updated), 'Menu updated successfully');
    }

    public function destroy($id): void
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            $this->error('Menu not found', 404);
        }

        $deleted = $this->menuModel->delete($id);
        if (!$deleted) {
            $this->error('Failed to delete menu', 500);
        }
        $this->success(null, 'Menu deleted successfully');
    }

    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        return trim($slug, '-');
    }
}
