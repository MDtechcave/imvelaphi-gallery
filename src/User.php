<?php

namespace Mihledudumashe\ImvelaphiGallery;

class User 
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

public function create(
    string $username,
    string $email,
    string $password
): int {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $this->db->prepare(
        'INSERT INTO users (username, email, password)
        VALUES (:username, :email, :password)'
    );

    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword
    ]);

    return (int) $this->db->lastInsertId();
}
}