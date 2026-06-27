<?php
namespace App\Models;

use App\Core\Database;
use App\Core\Pagination;

/**
 * ===========================================
 * BaseModel - Abstract CRUD Model
 * ===========================================
 * Sesuai materi §2.6: Semua model extends BaseModel.
 * Menyediakan generic CRUD operations via PDO Singleton.
 */
abstract class BaseModel
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find all records
     */
    public function findAll(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Find record by primary key
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $result = $this->db->query($sql, [$id])->fetch();
        return $result ?: null;
    }

    /**
     * Find records by column value
     */
    public function findBy(string $column, mixed $value): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        return $this->db->query($sql, [$value])->fetchAll();
    }

    /**
     * Find single record by column value
     */
    public function findOneBy(string $column, mixed $value): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
        $result = $this->db->query($sql, [$value])->fetch();
        return $result ?: null;
    }

    /**
     * Count all records
     */
    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        return (int) $this->db->query($sql, $params)->fetchColumn();
    }

    /**
     * Create a new record
     * 
     * @param array $data Associative array of column => value
     * @return int The ID of the newly created record
     */
    public function create(array $data): int
    {
        // Filter to only fillable fields
        $data = $this->filterFillable($data);

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->db->query($sql, array_values($data));

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update a record
     * 
     * @param int   $id   The primary key value
     * @param array $data Associative array of column => value
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        // Filter to only fillable fields
        $data = $this->filterFillable($data);

        $setParts = [];
        $values = [];
        foreach ($data as $column => $value) {
            $setParts[] = "{$column} = ?";
            $values[] = $value;
        }
        $values[] = $id;

        $setString = implode(', ', $setParts);
        $sql = "UPDATE {$this->table} SET {$setString} WHERE {$this->primaryKey} = ?";

        $this->db->query($sql, $values);
        return true;
    }

    /**
     * Delete a record
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $this->db->query($sql, [$id]);
        return true;
    }

    /**
     * Paginated query
     * 
     * @param int    $page    Current page number
     * @param int    $perPage Items per page
     * @param string $where   WHERE clause (without "WHERE" keyword)
     * @param array  $params  Bound parameters for WHERE clause
     * @param string $orderBy ORDER BY clause
     * @return array ['data' => [...], 'pagination' => Pagination]
     */
    public function paginate(
        int $page = 1,
        int $perPage = 10,
        string $where = '',
        array $params = [],
        string $orderBy = 'id DESC',
        string $baseUrl = ''
    ): array {
        // Count total
        $total = $this->count($where, $params);

        // Create pagination object
        $pagination = new Pagination($total, $perPage, $page, $baseUrl);

        // Fetch data with LIMIT/OFFSET
        $sql = "SELECT * FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $sql .= " ORDER BY {$orderBy}";
        $sql .= " LIMIT {$pagination->getLimit()} OFFSET {$pagination->getOffset()}";

        $data = $this->db->query($sql, $params)->fetchAll();

        return [
            'data'       => $data,
            'pagination' => $pagination,
        ];
    }

    /**
     * Execute raw query
     */
    public function raw(string $sql, array $params = []): \PDOStatement
    {
        return $this->db->query($sql, $params);
    }

    /**
     * Filter data to only include fillable fields
     */
    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Get database instance for transactions
     */
    protected function getDb(): Database
    {
        return $this->db;
    }
}
