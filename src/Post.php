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
    string $title,
    string $description,
    string $image,
    int $categoryId
): int{
    $stmt = $this->db->prepare(
        'INSERT INTO posts (user_id, title, description, image, category_id)
        VALUES (:user_id, :title, :description, :image, :category_id)'
    );

    $stmt->execute([
        'user_id' => $userId,
        'title' => trim($title),
        'description' => trim($description),
        'image'=> trim($image),
        'category_id' => $categoryId
    ]);

    return (int) $this->db->lastInsertId();
}  
}