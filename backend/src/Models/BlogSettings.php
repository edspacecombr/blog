<?php

namespace App\Models;

class BlogSettings extends Model
{
    protected string $table = 'blog_settings';
    protected array $fillable = [
        'site_name',
        'description',
        'tagline',
        'title_pattern',
        'robots_txt_content',
        'default_og_image_id',
        'setup_completed',
    ];

    /**
     * Get all settings as key-value
     */
    public function getAllAsArray(): array
    {
        $records = $this->all();
        $settings = [];
        
        foreach ($records as $record) {
            $settings[$record['key']] = $record['value'] ?? null;
        }

        return $settings;
    }

    /**
     * Get setting by key
     */
    public function get(string $key, $default = null)
    {
        $query = "SELECT value FROM {$this->table} WHERE key = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$key]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $result['value'] ?? $default;
    }

    /**
     * Set setting by key
     */
    public function set(string $key, $value): bool
    {
        $query = "
            INSERT INTO {$this->table} (key, value) 
            VALUES (?, ?)
            ON CONFLICT (key) DO UPDATE SET value = ?
        ";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$key, $value, $value]);
    }
}
