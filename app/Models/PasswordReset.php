<?php
namespace App\Models;

class PasswordReset extends BaseModel
{
    protected string $table = 'password_resets';
    protected array $fillable = ['email', 'token', 'expires_at', 'used'];

    /**
     * Create a reset token for an email
     */
    public function createToken(string $email): string
    {
        // Invalidate any existing tokens for this email
        $this->db->query(
            "UPDATE {$this->table} SET used = 1 WHERE email = ? AND used = 0",
            [$email]
        );

        $token = bin2hex(random_bytes(32)); // 64-char hex token
        $this->create([
            'email'      => $email,
            'token'      => $token,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'used'       => 0,
        ]);

        return $token;
    }

    /**
     * Find a valid (unexpired, unused) token
     */
    public function findValidToken(string $token): ?array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE token = ? AND used = 0 AND expires_at > NOW()
                LIMIT 1";
        $result = $this->db->query($sql, [$token])->fetch();
        return $result ?: null;
    }

    /**
     * Mark token as used
     */
    public function markUsed(string $token): void
    {
        $this->db->query(
            "UPDATE {$this->table} SET used = 1 WHERE token = ?",
            [$token]
        );
    }

    /**
     * Clean expired tokens
     */
    public function cleanExpired(): void
    {
        $this->db->query(
            "DELETE FROM {$this->table} WHERE expires_at < NOW() OR used = 1"
        );
    }
}
