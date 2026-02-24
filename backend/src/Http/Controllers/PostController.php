<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Api\Resources\PostResource;
use App\Services\HtmlSanitizationService;

class PostController extends BaseController
{
    private Post $postModel;
    private HtmlSanitizationService $sanitizer;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->sanitizer = new HtmlSanitizationService();
    }

    /**
     * GET /api/v1/posts
     * List posts with pagination and filters
     */
    public function index(): void
    {
        $page = (int)$this->getQueryParam('page', 1);
        $perPage = (int)$this->getQueryParam('per_page', 15);
        $status = $this->getQueryParam('status');
        $language = $this->getQueryParam('language', 'en');
        $categoryId = $this->getQueryParam('category_id');

        $filters = [];
        if ($status) {
            $filters['status'] = $status;
        }
        if ($language) {
            $filters['language'] = $language;
        }
        if ($categoryId) {
            $filters['category_id'] = $categoryId;
        }

        $result = $this->postModel->paginate($page, $perPage, $filters);
        
        $data = array_map(fn($post) => (new PostResource($post))->toArray(), $result['data']);
        $response = [
            'data' => $data,
            'pagination' => $result['pagination'],
        ];

        $this->success($response);
    }

    /**
     * GET /api/v1/posts/{id}
     * Get single post
     */
    public function show($id): void
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            $this->error('Post not found', 404);
        }

        $this->postModel->incrementViewCount($id);
        $this->success(new PostResource($post));
    }

    /**
     * POST /api/v1/posts
     * Create new post
     */
    public function store(): void
    {
        $data = $this->getJsonBody();
        
        $this->validateRequired($data, ['title', 'content', 'language']);

        // Sanitizar HTML antes de salvar
        $postData = [
            'title' => trim($data['title']),
            'slug' => $this->generateSlug($data['title']),
            'content' => $this->sanitizer->sanitize($data['content']),
            'excerpt' => isset($data['excerpt']) ? $this->sanitizer->sanitize($data['excerpt']) : '',
            'language' => $data['language'] ?? 'en',
            'status' => $data['status'] ?? 'draft',
            'author_id' => $data['author_id'] ?? 1,
            'featured_image_id' => $data['featured_image_id'] ?? null,
            'seo_title' => isset($data['seo_title']) ? trim($data['seo_title']) : null,
            'seo_description' => isset($data['seo_description']) ? trim($data['seo_description']) : null,
            'seo_keywords' => isset($data['seo_keywords']) ? trim($data['seo_keywords']) : null,
        ];

        $post = $this->postModel->create($postData);
        
        if (!$post) {
            $this->error('Failed to create post', 500);
        }

        $this->success(new PostResource($post), 'Post created successfully', 201);
    }

    /**
     * PATCH /api/v1/posts/{id}
     * Update post
     */
    public function update($id): void
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            $this->error('Post not found', 404);
        }

        $data = $this->getJsonBody();
        
        $postData = [];
        $allowedFields = ['title', 'content', 'excerpt', 'status', 'featured_image_id', 'scheduled_at', 'published_at', 'seo_title', 'seo_description', 'seo_keywords'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                if ($field === 'title' && $data[$field] !== $post['title']) {
                    $postData['slug'] = $this->generateSlug($data[$field]);
                    $postData[$field] = trim($data[$field]);
                } elseif (in_array($field, ['content', 'excerpt'])) {
                    // Sanitizar HTML
                    $postData[$field] = $this->sanitizer->sanitize($data[$field]);
                } else {
                    $postData[$field] = $data[$field];
                }
            }
        }

        if (empty($postData)) {
            $this->error('No fields to update', 422);
        }

        $updated = $this->postModel->update($id, $postData);
        
        if (!$updated) {
            $this->error('Failed to update post', 500);
        }

        $this->success(new PostResource($updated), 'Post updated successfully');
    }

    /**
     * DELETE /api/v1/posts/{id}
     * Delete post
     */
    public function destroy($id): void
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            $this->error('Post not found', 404);
        }

        $deleted = $this->postModel->delete($id);
        
        if (!$deleted) {
            $this->error('Failed to delete post', 500);
        }

        $this->success(null, 'Post deleted successfully');
    }

    /**
     * Generate slug from title
     */
    private function generateSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}

    /**
     * PATCH /api/v1/posts/{id}/publish
     * F6.3: Publish post and trigger revalidation
     */
    public function publish($id): void
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            $this->error('Post not found', 404);
        }

        $updated = $this->postModel->update($id, [
            'status' => 'published',
            'published_at' => date('Y-m-d H:i:s'),
        ]);

        if (!$updated) {
            $this->error('Failed to publish post', 500);
        }

        // F6.5: Trigger Next.js revalidation
        $revalidationService = new \App\Services\NextjsRevalidationService();
        $slug = $updated->slug ?? 'post';
        $revalidated = $revalidationService->revalidatePost($slug);

        $this->success([
            'post' => new PostResource($updated),
            'revalidated' => $revalidated,
        ], 'Post published successfully');
    }
