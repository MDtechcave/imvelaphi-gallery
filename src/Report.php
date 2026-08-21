<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Report
{
    private \PDO $db;
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    int $userId,
    string $contentType,
    ?int $postId,
    ?int $commentId,
    ?int $reportedUserId,
    string $reason
): int {
    $stmt = $this->db->prepare(
        'INSERT INTO content_reports 
        (user_id, content_type, post_id, comment_id, reported_user_id, reason)
        VALUES 
        (:user_id, :content_type, :post_id, :comment_id, :reported_user_id, :reason)'
    );

    $stmt->execute([
        'user_id' => $userId,
        'content_type' => $contentType,
        'post_id' => $postId,
        'comment_id' => $commentId,
        'reported_user_id' => $reportedUserId,
        'reason' => $reason
    ]);

    return (int) $this->db->lastInsertId();
}
}