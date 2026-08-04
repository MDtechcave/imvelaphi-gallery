<?php 

namespace Mihledudumashe\ImvelaphiGallery;

class Tribe
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    int $cultureId,
    string $name
): int {
    $stmt = $this->db->prepare(
        'INSERT INTO tribes (culture_id, name)
        VALUES (:culture_id, :name)'
    );

    $stmt->execute([
        'culture_id' => $cultureId,
        'name' => trim($name)
    ]);

    return (int) $this->db->lastInsertId();
}
}