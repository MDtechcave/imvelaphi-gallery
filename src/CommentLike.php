<?php

namespace Mihledudumashe\ImvelaphiGallery;

class CommentLike
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    // Like a comment
    public function create(
        int $commentId,
        int $userId
    ): int {
        $stmt = $this->db->prepare(
            'INSERT INTO comment_likes (comment_id, user_id)
             VALUES (:comment_id, :user_id)'
        );

        $stmt->execute([
            'comment_id' => $commentId,
            'user_id' => $userId
        ]);

        return (int) $this->db->lastInsertId();
    }

    // Unlike a comment
    public function delete(
        int $commentId,
        int $userId
    ): bool {
        $stmt = $this->db->prepare(
            'DELETE FROM comment_likes
             WHERE comment_id = :comment_id
             AND user_id = :user_id'
        );

        return $stmt->execute([
            'comment_id' => $commentId,
            'user_id' => $userId
        ]);
    }

    // Check whether the user has liked the comment
    public function exists(
        int $commentId,
        int $userId
    ): bool {
        $stmt = $this->db->prepare(
            'SELECT id
             FROM comment_likes
             WHERE comment_id = :comment_id
             AND user_id = :user_id'
        );

        $stmt->execute([
            'comment_id' => $commentId,
            'user_id' => $userId
        ]);

        return $stmt->fetch() !== false;
    }
}