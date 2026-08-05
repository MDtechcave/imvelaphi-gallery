<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Comment
{
    private \PDO $db;
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }
    
public function create (
    int $userId,
    int $postId,
    string $comment
):int{
    $stmt = $this->db->prepare(
        'INSERT INTO comments (user_id, post_id, comment)
        VALUES (:user_id, :post_id, :comment)'
    );
    $stmt->execute([
        'user_id' => $userId,
        'post_id' => $postId,
        'comment' => trim($comment)
    ]);
    return (int) $this->db->lastInsertId();
}

}