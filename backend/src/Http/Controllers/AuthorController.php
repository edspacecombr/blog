<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Api\Resources\AuthorResource;

class AuthorController extends BaseController
{
    private Author $authorModel;

    public function __construct()
    {
        $this->authorModel = new Author();
    }

    public function index(): void
    {
        $page = (int)$this->getQueryParam('page', 1);
        $perPage = (int)$this->getQueryParam('per_page', 15);

        $result = $this->authorModel->getAuthors($page, $perPage);
        
        $data = array_map(fn($item) => (new AuthorResource($item))->toArray(), $result['data']);
        $response = ['data' => $data, 'pagination' => $result['pagination']];

        $this->success($response);
    }

    public function show($id): void
    {
        $author = $this->authorModel->find($id);
        if (!$author) {
            $this->error('Author not found', 404);
        }
        $this->success(new AuthorResource($author));
    }

    public function store(): void
    {
        $data = $this->getJsonBody();
        $this->validateRequired($data, ['name', 'email']);

        $bio = $data['bio'] ?? [];
        if (is_array($bio)) {
            $bio = json_encode($bio);
        }

        $socialLinks = $data['social_links'] ?? [];
        if (is_array($socialLinks)) {
            $socialLinks = json_encode($socialLinks);
        }

        $authorData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'bio' => $bio,
            'avatar_path' => $data['avatar_path'] ?? null,
            'social_links' => $socialLinks,
            'schema_same_as' => $data['schema_same_as'] ?? null,
        ];

        $author = $this->authorModel->create($authorData);
        if (!$author) {
            $this->error('Failed to create author', 500);
        }
        $this->success(new AuthorResource($author), 'Author created successfully', 201);
    }

    public function update($id): void
    {
        $author = $this->authorModel->find($id);
        if (!$author) {
            $this->error('Author not found', 404);
        }

        $data = $this->getJsonBody();
        $authorData = [];
        $allowedFields = ['name', 'bio', 'avatar_path', 'social_links', 'schema_same_as'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                if (in_array($field, ['bio', 'social_links']) && is_array($data[$field])) {
                    $authorData[$field] = json_encode($data[$field]);
                } else {
                    $authorData[$field] = $data[$field];
                }
            }
        }

        if (empty($authorData)) {
            $this->error('No fields to update', 422);
        }

        $updated = $this->authorModel->update($id, $authorData);
        if (!$updated) {
            $this->error('Failed to update author', 500);
        }
        $this->success(new AuthorResource($updated), 'Author updated successfully');
    }

    public function destroy($id): void
    {
        $author = $this->authorModel->find($id);
        if (!$author) {
            $this->error('Author not found', 404);
        }

        $deleted = $this->authorModel->delete($id);
        if (!$deleted) {
            $this->error('Failed to delete author', 500);
        }
        $this->success(null, 'Author deleted successfully');
    }
}
