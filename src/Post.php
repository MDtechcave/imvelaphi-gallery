<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Post
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    int $userId,
    int $cultureId,
    int $tribeId,
    string $title,
    string $description,
    string $image
): int{
    $stmt = $this->db->prepare(
        'INSERT INTO posts (user_id, tribe_id, culture_id, title, description, image)
        VALUES (:user_id, :tribe_id, :culture_id, :title, :description, :image)'
    );

    $stmt->execute([
        'user_id' => $userId,
        'culture_id' => $cultureId,
        'tribe_id' => $tribeId,
        'title' => trim($title),
        'description' => trim($description),
        'image'=> trim($image)
    ]);

    return (int) $this->db->lastInsertId();
}  




}