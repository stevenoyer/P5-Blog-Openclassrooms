<?php 

namespace So\Blog\Model;

use So\Blog\Class\Model;

class CommentsModel extends Model
{
    protected $table = 'comments';

    /**
     * @param int $id
     * @return array
     */
    public function findCommentByPostId(int $id): array|object|bool
    {
        return $this->query("
            SELECT c.*, u.name AS author_name
            FROM $this->table AS c
            LEFT JOIN users AS u ON u.id = c.author
            WHERE c.post_id = ? AND validation = ?
            ORDER BY c.created_at DESC
        ", [$id, 1], false);
    }

}
