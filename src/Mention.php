<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Mention
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    int $postId,
    int $mentionedUserId
): int{
    $stmt = $this->db->prepare(
        'INSERT INTO post_mentions (post_id, mentioned_user_id)
        VALUES (:post_id, :mentioned_user_id)'
    );

    $stmt->execute([
        'post_id' => $postId,
        'mentioned_user_id' => $mentionedUserId
    ]);

    return (int) $this->db->lastInsertId();
}
}