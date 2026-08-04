<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Culture
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    string $name
    ): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO cultures (name)
            VALUES (:name)'
        );

        $stmt->execute([
            'name' => trim($name)
        ]);

        return (int) $this->db->lastInsertId();
    }
}