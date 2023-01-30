<?php 

namespace So\Blog\Model;

use So\Blog\Class\Model;

class ArticlesModel extends Model
{
    protected $table = 'posts';

    public function read(int $limit = 5): array|object
    {
        $limit_sql = '';
        if ($limit != 0)
        {
            $limit_sql = "LIMIT $limit";
        }

        return $this->query("
            SELECT p.*, u.name AS author_name
            FROM $this->table AS p
            LEFT JOIN users AS u ON u.id = p.author
            WHERE p.state = 1
            ORDER BY p.created_at DESC
            $limit_sql
        ");
    }

    /**
     * @param int $id
     * @return array
     */
    public function find(int $id): array|object
    {
        return $this->query("
            SELECT p.*, u.name AS author_name
            FROM $this->table AS p
            LEFT JOIN users AS u ON u.id = p.author
            WHERE p.id = ? AND p.state = ?
            ORDER BY p.created_at DESC
        ", [$id, 1], true);
    }

}
