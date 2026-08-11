<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Follower
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    int $followingId,
    int $followerId
): int{
    $stmt = $this->db->prepare(
    'INSERT INTO followers (following_id, follower_id)
     VALUES (:following_id, :follower_id)'
    );

    $stmt->execute([
        'following_id' => $followingId,
        'follower_id' => $followerId
    ]);

    return (int) $this->db->lastInsertId();
}

}