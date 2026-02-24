<?php

namespace App\Models;

class Language extends Model
{
    protected string $table = 'languages';
    protected array $fillable = [
        'code',
        'name',
        'native_name',
        'direction',
        'locale',
        'is_enabled',
        'is_primary',
    ];

    /**
     * Get default language
     */
    public function getDefault(): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE is_primary = true LIMIT 1";
        $stmt = $this->db->query($query);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get active languages
     */
    public function getActive(): array
    {
        $query = "SELECT * FROM {$this->table} WHERE is_enabled = true ORDER BY is_primary DESC, code ASC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get by code
     */
    public function findByCode(string $code): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE code = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$code]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
}
