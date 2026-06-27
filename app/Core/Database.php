<?php
namespace App\Core;

/**
 * ===========================================
 * PDO Singleton - Database Connection
 * ===========================================
 * Sesuai materi §2.6 BaseModel dengan PDO Singleton.
 * Hanya 1 koneksi PDO selama lifecycle aplikasi.
 */
class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;

    /**
     * Private constructor - Singleton Pattern
     */
    private function __construct()
    {
        $config = require CONFIG_PATH . '/database.php';

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            $this->pdo = new \PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (\PDOException $e) {
            if (APP_DEBUG) {
                throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
            }
            throw new \RuntimeException('Database connection failed. Please check your configuration.');
        }
    }

    /**
     * Prevent cloning of Singleton
     */
    private function __clone() {}

    /**
     * Get Singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get PDO connection
     */
    public function getConnection(): \PDO
    {
        return $this->pdo;
    }

    /**
     * Shortcut: prepare and execute a query
     * 
     * @param string $sql    SQL query with placeholders
     * @param array  $params Bound parameters
     * @return \PDOStatement
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Get last inserted ID
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }
}
