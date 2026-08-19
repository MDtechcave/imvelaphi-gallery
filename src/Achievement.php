<?php 

namespace Mihledudumashe\ImvelaphiGallery;

class Achievement
{
    private \PDO $db;
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create (
    int $userId,
    string $badgeType
): int {
    $stmt = $this->db->prepare(
        'INSERT INTO user_achievements (user_id, badge_type)
        VALUES (:user_id, :badge_type)'
    );

    $stmt->execute([
        'user_id' => $userId,
        'badge_type' => trim($badgeType)
    ]);
    return (int) $this->db->lastInsertId();

}
}