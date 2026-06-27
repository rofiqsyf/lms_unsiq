<?php
namespace App\Models;

use App\Core\Database;

/**
 * User Model
 */
class User extends BaseModel
{
    protected string $table = 'users';
    protected array $fillable = [
        'name', 'email', 'password', 'role', 'nim_nidn',
        'phone', 'avatar', 'bio', 'is_active', 'last_login_at'
    ];

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?array
    {
        return $this->findOneBy('email', $email);
    }

    /**
     * Authenticate user
     */
    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);

        if (!$user) return null;
        if (!$user['is_active']) return null;
        if (!password_verify($password, $user['password'])) return null;

        // Update last login
        $this->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        // Remove password from return
        unset($user['password']);
        return $user;
    }

    /**
     * Create user with hashed password
     */
    public function createUser(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $this->create($data);
    }

    /**
     * Update user password
     */
    public function updatePassword(int $id, string $newPassword): bool
    {
        return $this->update($id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);
    }

    /**
     * Get users by role
     */
    public function getByRole(string $role): array
    {
        return $this->findBy('role', $role);
    }

    /**
     * Count users by role
     */
    public function countByRole(string $role): int
    {
        return $this->count('role = ?', [$role]);
    }

    /**
     * Paginate users with optional search and role filter
     */
    public function paginateUsers(int $page = 1, int $perPage = 10, string $search = '', string $role = '', string $baseUrl = ''): array
    {
        $where = '1=1';
        $params = [];

        if ($search) {
            $where .= ' AND (name LIKE ? OR email LIKE ? OR nim_nidn LIKE ?)';
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if ($role) {
            $where .= ' AND role = ?';
            $params[] = $role;
        }

        return $this->paginate($page, $perPage, $where, $params, 'created_at DESC', $baseUrl);
    }

    /**
     * Get recently registered users
     */
    public function getRecent(int $limit = 5): array
    {
        $sql = "SELECT id, name, email, role, avatar, created_at FROM {$this->table} ORDER BY created_at DESC LIMIT ?";
        return $this->db->query($sql, [$limit])->fetchAll();
    }

    /**
     * Get all dosen for dropdown
     */
    public function getAllDosen(): array
    {
        return $this->raw(
            "SELECT id, name, nim_nidn FROM users WHERE role = 'dosen' AND is_active = 1 ORDER BY name"
        )->fetchAll();
    }
}
