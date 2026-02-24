<?php

namespace App\Models;

class Author extends Model
{
    protected string $table = 'users';
    protected array $fillable = [
        'name',
        'email',
        'bio',
        'avatar_path',
        'social_links',
        'schema_same_as',
    ];

    /**
     * Get authors (users with role)
     */
    public function getAuthors(int $page = 1, int $perPage = 15): array
    {
        $offset = ($page - 1) * $perPage;
        
        $countQuery = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->prepare($countQuery);
        $stmt->execute();
        $total = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['total'];

        $query = "SELECT * FROM {$this->table} ORDER BY name LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$perPage, $offset]);
        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return [
            'data' => $records,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
            ],
        ];
    }

    /**
     * Get author by email
     */
    public function findByEmail(string $email): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
}
