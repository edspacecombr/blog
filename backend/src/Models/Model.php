<?php

namespace App\Models;

use PDO;
use App\Database\Connection;

class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $casts = [];

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Get all records with pagination
     */
    public function paginate(int $page = 1, int $perPage = 15, array $filters = []): array
    {
        $offset = ($page - 1) * $perPage;
        
        $query = "SELECT * FROM {$this->table}";
        $countQuery = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];

        // Apply filters
        if (!empty($filters)) {
            $whereConditions = [];
            foreach ($filters as $column => $value) {
                $whereConditions[] = "$column = ?";
                $params[] = $value;
            }
            if ($whereConditions) {
                $where = " WHERE " . implode(" AND ", $whereConditions);
                $query .= $where;
                $countQuery .= $where;
            }
        }

        // Get total count
        $stmt = $this->db->prepare($countQuery);
        $stmt->execute($params);
        $total = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get paginated records
        $query .= " ORDER BY id DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $records,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total),
            ],
        ];
    }

    /**
     * Get all records
     */
    public function all(array $filters = []): array
    {
        $query = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($filters)) {
            $whereConditions = [];
            foreach ($filters as $column => $value) {
                $whereConditions[] = "$column = ?";
                $params[] = $value;
            }
            if ($whereConditions) {
                $query .= " WHERE " . implode(" AND ", $whereConditions);
            }
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find record by ID
     */
    public function find($id): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Find record by column
     */
    public function findBy(string $column, $value): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE $column = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$value]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Create new record
     */
    public function create(array $data): ?array
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders) RETURNING *";

        $stmt = $this->db->prepare($query);
        $stmt->execute(array_values($data));
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Update record
     */
    public function update($id, array $data): ?array
    {
        $data['updated_at'] = date('Y-m-d H:i:s');

        $sets = [];
        $params = [];
        foreach ($data as $column => $value) {
            $sets[] = "$column = ?";
            $params[] = $value;
        }
        $params[] = $id;

        $setClause = implode(", ", $sets);
        $query = "UPDATE {$this->table} SET $setClause WHERE {$this->primaryKey} = ? RETURNING *";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Delete record
     */
    public function delete($id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    /**
     * Check if record exists
     */
    public function exists($id): bool
    {
        $query = "SELECT 1 FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch() !== false;
    }

    /**
     * Count records
     */
    public function count(array $filters = []): int
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];

        if (!empty($filters)) {
            $whereConditions = [];
            foreach ($filters as $column => $value) {
                $whereConditions[] = "$column = ?";
                $params[] = $value;
            }
            if ($whereConditions) {
                $query .= " WHERE " . implode(" AND ", $whereConditions);
            }
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
