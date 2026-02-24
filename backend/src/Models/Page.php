<?php

namespace App\Models;

class Page extends Model
{
    protected string $table = 'pages';
    protected array $fillable = [
        'title',
        'slug',
        'content',
        'language',
        'status',
        'seo_title',
        'seo_description',
    ];

    /**
     * Get page by slug
     */
    public function findBySlug(string $slug, string $language = 'en'): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE slug = ? AND language = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$slug, $language]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get pages by status
     */
    public function getByStatus(string $status, string $language = 'en'): array
    {
        $query = "SELECT * FROM {$this->table} WHERE status = ? AND language = ? ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$status, $language]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
