<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Report
{
    private \PDO $db;
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create (
    int $reporterId,
    ?int $postId,
    ?int $commentId,
    ?int $reportedUserId,
    string $reason,
    string $contentType
): int {
    $stmt = $this->db->prepare(
        'INSERT INTO content_reports (reporter_id, content_type, post_id, comment_id, reported_user_id, reason)
        VALUES (:reporter_id, :content_type, :post_id, :comment_id, :reported_user_id, :reason)'
    );

    $stmt->execute([
        'reporterId' => $reporterId,
        'content_type' => $contentType,
        'post_id' => $postId,
        'comment_type' => $commentId,
        'reported_user_id' => $reportedUserId,
        'reason' => $reason
    ]);

    return (int) $this->db->lastInsertId();
}

}