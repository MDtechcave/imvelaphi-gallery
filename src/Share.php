<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Share
{
    private \PDO $db;
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create (
    int $userId,
    int $postId,
    string $platform
): int {
    $stmt = $this->db->prepare(
        'INSERT INTO shares (user_id, post_id, platform)
        VALUES (:user_id, :post_id, :platform)'
    );
    $stmt->execute([
        'user_id' => $userId,
        'post_id' => $postId,
        'platform' => trim($platform)
    ]);
    return (int) $this->db->lastInsertId();
}
}