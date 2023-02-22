<?php 

namespace So\Blog\Model;

use So\Blog\Class\Model;

class CommentsModel extends Model
{
    protected $table = 'comments';

    /**
     * Read comments items
     */
    public function read(int $limit = 5): array|object|bool
    {
        $limit_sql = '';
        if ($limit != 0)
        {
            $limit_sql = "LIMIT $limit";
        }

        return $this->query("
            SELECT c.*, COALESCE(u.name, 'Utilisateur supprimé') AS author_name, p.title as post_title
            FROM $this->table AS c
            LEFT JOIN users AS u ON u.id = c.author
            LEFT JOIN posts AS p ON p.id = c.post_id
            ORDER BY c.created_at DESC
            $limit_sql
        ");
    }

    /**
     * Find comments by post id
     */
    public function findCommentsByPostId(int $id): mixed
    {
        return $this->query("
            SELECT c.*, COALESCE(u.name, 'Utilisateur supprimé') AS author_name
            FROM $this->table AS c
            LEFT JOIN users AS u ON u.id = c.author
            WHERE c.post_id = ? AND validation = ?
            ORDER BY c.created_at DESC
        ", [$id, 1], false);
    }

    /**
     * Find comments by user id
     */
    public function findCommentsByUserId(int $id): mixed
    {
        return $this->query("
            SELECT c.*, p.title as post_title
            FROM $this->table AS c
            LEFT JOIN posts AS p ON p.id = c.post_id
            WHERE c.author = ?
            ORDER BY c.update_at DESC;
        ", [$id], false);
    }

}
