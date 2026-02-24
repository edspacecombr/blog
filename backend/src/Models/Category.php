<?php

namespace App\Models;

class Category extends Model
{
    protected string $table = 'categories';
    protected array $fillable = [
        'name',
        'slug',
        'description',
        'language',
        'parent_id',
        'display_order',
        'color',
        'icon',
    ];

    /**
     * Get category by slug
     */
    public function findBySlug(string $slug, string $language = 'en'): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE slug = ? AND language = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$slug, $language]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get root categories (no parent)
     */
    public function getRootCategories(string $language = 'en'): array
    {
        $query = "SELECT * FROM {$this->table} WHERE parent_id IS NULL AND language = ? ORDER BY display_order, name";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$language]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get subcategories
     */
    public function getSubcategories(int $parentId): array
    {
        $query = "SELECT * FROM {$this->table} WHERE parent_id = ? ORDER BY display_order, name";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$parentId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get categories tree
     */
    public function getTree(string $language = 'en'): array
    {
        $roots = $this->getRootCategories($language);
        $tree = [];

        foreach ($roots as $root) {
            $root['subcategories'] = $this->getSubcategories($root['id']);
            $tree[] = $root;
        }

        return $tree;
    }
}
