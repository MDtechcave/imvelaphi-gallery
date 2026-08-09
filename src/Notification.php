<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Notification 
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    int $userId,
    int $relatedUserId,
    int $postId,
    string $type,
    bool $isRead
): int{
    $stmt = $this->db->prepare(
        'INSERT INTO notifications (user_id, related_user_id, post_id, type, is_read)
        VALUES (:user_id, :related_user_id, :post_id, :type, :is_read)'
    );

    $stmt->execute([
        'user_id' => $userId,
        'related_user_id' => $relatedUserId,
        'post_id' => $postId,
        'type' => trim($type),
        'is_read' => (int)$isRead
    ]);

        return (int) $this->db->lastInsertId();
}
}