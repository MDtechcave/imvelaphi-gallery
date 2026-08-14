<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Tag
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    int $postId,
    string $tag
): int{
    $stmt = $this->db->prepare(
        'INSERT INTO post_tags (post_id, tag)
        VALUES (:post_id, :tag)'
    );

    $stmt->execute([
        'post_id' => $postId,
        'tag' => trim($tag)
    ]);

    return (int) $this->db->lastInsertId();
}
}