<?php 

namespace So\Blog\Model;

use So\Blog\Class\Model;

class ArticlesModel extends Model
{
    protected $table = 'posts';

    /**
     * Read posts items
     */
    public function read(int $limit = 5, ?int $state = 1): array|object|bool
    {
        $limit_sql = '';
        if ($limit != 0)
        {
            $limit_sql = "LIMIT $limit";
        }

        if (!is_null($state))
        {
            $state = "WHERE p.state = $state";
        }

        return $this->query("
            SELECT p.*, COALESCE(u.name, 'Utilisateur supprimé') AS author_name
            FROM $this->table AS p
            LEFT JOIN users AS u ON u.id = p.author
            $state
            ORDER BY p.created_at DESC
            $limit_sql
        ");
    }
    
    /**
     * Get post by search
     */
    public function find(mixed $search, string $type = 'id', bool $one = true): array|object|bool
    {
        return $this->query("
            SELECT p.*, COALESCE(u.name, 'Utilisateur supprimé') AS author_name
            FROM $this->table AS p
            LEFT JOIN users AS u ON u.id = p.author
            WHERE p.$type = ? AND p.state = ?
            ORDER BY p.created_at DESC
        ", [$search, 1], $one);
    }

}
