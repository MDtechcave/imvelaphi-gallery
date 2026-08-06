<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Bookmark
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    int $userId,
    int $postId
): int{
    $stmt = $this->db->prepare(
        'INSERT INTO bookmarks (user_id, post_id)
        VALUES (:user_id, :post_id)'
    );

    $stmt->execute([
        'user_id' => $userId,
        'post_id' => $postId
    ]);

    return (int) $this->db->lastInsertId();
}

}