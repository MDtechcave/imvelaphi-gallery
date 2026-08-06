<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Category
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    string $icon,
    string $name
): int 
{
    $stmt = $this->db->prepare(
        'INSERT INTO categories (icon, name)
        VALUES (:icon, :name)'
    );

    $stmt->execute([
        'icon' => trim($icon),
        'name' => trim($name)
    ]);

    return (int) $this->db->lastInsertId();
    }
}