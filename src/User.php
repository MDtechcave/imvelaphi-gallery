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
public function findByEmail(string $email): ?array
{
    $stmt = $this->db->prepare(
        'SELECT id, username, email, password
        FROM users
        WHERE email = :email
        LIMIT 1'
    );

    $stmt->execute([
        'email' => $email
    ]);

    $user = $stmt->fetch();

    return $user ?: null;
}

public function authenticate(string $email, string $password): ?array
{
    $user = $this->findByEmail($email);

    if ($user === null) {
        return null;
    }

    if (!password_verify($password, $user['password'])) {
        return null;
    }

    return $user;
}
}