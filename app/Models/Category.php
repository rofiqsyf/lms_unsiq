<?php
namespace App\Models;

/**
 * Category Model
 */
class Category extends BaseModel
{
    protected string $table = 'categories';
    protected array $fillable = ['name', 'slug', 'description'];

    /**
     * Get all categories with course count
     */
    public function getAllWithCount(): array
    {
        $sql = "SELECT c.*, (SELECT COUNT(*) FROM courses co WHERE co.category_id = c.id) as course_count
                FROM categories c ORDER BY c.name";
        return $this->db->query($sql)->fetchAll();
    }
}
