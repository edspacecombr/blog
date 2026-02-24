<?php

namespace App\Controllers;

use App\Database\Connection;
use App\Services\SanitizationService;

class PostController
{
    protected $pdo;
    protected $sanitizer;

    public function __construct()
    {
        $env_file = __DIR__ . '/../../.env';
        if (file_exists($env_file)) {
            $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    putenv(trim($key) . '=' . trim($value));
                }
            }
        }
        $this->pdo = Connection::getInstance();
        $this->sanitizer = new SanitizationService();
    }

    /**
     * List all posts with pagination and filters
     */
    public function index($request)
    {
        $page = (int)($request['page'] ?? 1);
        $per_page = (int)($request['per_page'] ?? 15);
        $status = $request['status'] ?? 'published';
        $language = $request['language'] ?? 'pt';

        $offset = ($page - 1) * $per_page;

        // Build query
        $where = "status = ? AND language = ?";
        $params = [$status, $language];

        // Total count
        $count_sql = "SELECT COUNT(*) as total FROM posts WHERE $where";
        $stmt = $this->pdo->prepare($count_sql);
        $stmt->execute($params);
        $total_row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total = $total_row['total'] ?? 0;

        // Get posts with author
        $sql = "SELECT p.id, p.author_id, p.title, p.slug, p.content, p.excerpt, 
                       p.language, p.status, p.featured_image_id, p.seo_title, 
                       p.seo_description, p.views_count, p.published_at, p.created_at, p.updated_at,
                       a.name as author_name, a.slug as author_slug
                FROM posts p
                LEFT JOIN authors a ON p.author_id = a.id
                WHERE $where
                ORDER BY p.published_at DESC, p.created_at DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->pdo->prepare($sql);
        $exec_params = $params;
        $exec_params[] = $per_page;
        $exec_params[] = $offset;
        $stmt->execute($exec_params);
        $posts = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return [
            'data' => $posts,
            'meta' => [
                'total' => $total,
                'per_page' => $per_page,
                'current_page' => $page,
                'last_page' => (int)ceil($total / $per_page),
            ],
            'links' => [
                'next' => $page < ceil($total / $per_page) ? "/api/v1/posts?page=" . ($page + 1) : null,
                'prev' => $page > 1 ? "/api/v1/posts?page=" . ($page - 1) : null,
            ]
        ];
    }

    /**
     * Get single post
     */
    public function show($id, $language = 'pt')
    {
        $sql = "SELECT p.id, p.author_id, p.title, p.slug, p.content, p.excerpt, 
                       p.language, p.status, p.featured_image_id, p.seo_title, 
                       p.seo_description, p.views_count, p.published_at, p.created_at, p.updated_at,
                       a.name as author_name, a.slug as author_slug, a.bio as author_bio
                FROM posts p
                LEFT JOIN authors a ON p.author_id = a.id
                WHERE p.id = ? AND p.language = ?
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id, $language]);
        $post = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$post) {
            return ['error' => 'Post not found'];
        }

        return $post;
    }

    /**
     * Create new post
     */
    public function store($data)
    {
        // Validate
        $errors = [];
        if (empty($data['title'])) $errors[] = 'Title is required';
        if (empty($data['content'])) $errors[] = 'Content is required';
        if (empty($data['author_id'])) $errors[] = 'Author ID is required';
        if (empty($data['language'])) $data['language'] = 'pt';

        if ($errors) {
            return ['errors' => $errors];
        }

        try {
            // Sanitizar conteúdo HTML
            $sanitized_content = $this->sanitizer->sanitize($data['content']);
            $sanitized_excerpt = isset($data['excerpt']) ? $this->sanitizer->sanitize($data['excerpt']) : null;
            
            $slug = $data['slug'] ?? $this->generateSlug($data['title']);

            $post_sql = "INSERT INTO posts (author_id, title, slug, content, excerpt, language, status) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)
                         RETURNING id";
            $stmt = $this->pdo->prepare($post_sql);
            $stmt->execute([
                $data['author_id'],
                $data['title'],
                $slug,
                $sanitized_content,
                $sanitized_excerpt,
                $data['language'],
                $data['status'] ?? 'draft',
            ]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $post_id = $result['id'];

            return [
                'success' => true,
                'id' => $post_id,
                'slug' => $slug,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Update post
     */
    public function update($id, $data)
    {
        try {
            $updates = [];
            $params = [];

            if (isset($data['title'])) {
                $updates[] = 'title = ?';
                $params[] = $data['title'];
            }
            if (isset($data['content'])) {
                $updates[] = 'content = ?';
                $sanitized_content = $this->sanitizer->sanitize($data['content']);
                $params[] = $sanitized_content;
            }
            if (isset($data['excerpt'])) {
                $updates[] = 'excerpt = ?';
                $sanitized_excerpt = $this->sanitizer->sanitize($data['excerpt']);
                $params[] = $sanitized_excerpt;
            }
            if (isset($data['status'])) {
                $updates[] = 'status = ?';
                $params[] = $data['status'];
            }
            if (isset($data['slug'])) {
                $updates[] = 'slug = ?';
                $params[] = $data['slug'];
            }
            if (isset($data['seo_title'])) {
                $updates[] = 'seo_title = ?';
                $params[] = $data['seo_title'];
            }
            if (isset($data['seo_description'])) {
                $updates[] = 'seo_description = ?';
                $params[] = $data['seo_description'];
            }

            if (!$updates) {
                return ['error' => 'No fields to update'];
            }

            $updates[] = 'updated_at = NOW()';
            $sql = "UPDATE posts SET " . implode(', ', $updates) . " WHERE id = ?";
            $params[] = $id;

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return ['success' => true];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Delete post
     */
    public function destroy($id)
    {
        try {
            $sql = "DELETE FROM posts WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);

            return ['success' => true];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    protected function generateSlug($title)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $slug = trim($slug, '-');

        $count = 1;
        $original_slug = $slug;
        while ($this->slugExists($slug)) {
            $slug = $original_slug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    protected function slugExists($slug)
    {
        $sql = "SELECT COUNT(*) as count FROM posts WHERE slug = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$slug]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }
}
