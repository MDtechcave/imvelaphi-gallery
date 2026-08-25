<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Moderator
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function reviewReport(
        int $reportId,
        int $moderatorId
    ): bool {
        
        // Check if the user is actually a moderator or admin
        $stmt = $this->db->prepare(
            'SELECT role
             FROM users
             WHERE id = :id'
        );

        $stmt->execute(['id' => $moderatorId]);

        $user = $stmt->fetch();

        if (!$user) {
            throw new \RuntimeException('User not found.');
        }

        if (!in_array($user['role'], ['moderator', 'admin'], true)) {
            throw new \RuntimeException(
                'User is not authorized to review reports.VOETSEK!'
            );
        }

        // Update the report
        $stmt = $this->db->prepare(
            'UPDATE content_reports
             SET status = :status,
                 reviewed_by = :reviewed_by,
                 reviewed_at = NOW()
             WHERE id = :id'
        );

        $stmt->execute([
            'status' => 'reviewed',
            'reviewed_by' => $moderatorId,
            'id' => $reportId
        ]);

        return true;
    }
}