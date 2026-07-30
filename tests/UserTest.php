<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private \PDO $db;

    protected function setUp(): void
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function testUserCanBeCreated(): void
    {
        $user = new User($this->db);

        $userId = $user->create(
            'testuser',
            'test@example.com',
            'Password123!'
        );

        $this->assertIsInt($userId);
        $this->assertGreaterThan(0, $userId);

        // Checks that the user actually exists.
        $stmt = $this->db->prepare(
            'SELECT username, email, password
             FROM users
             WHERE id = :id'
        );

        $stmt->execute(['id' => $userId]);

        $createdUser = $stmt->fetch();

        $this->assertSame('testuser', $createdUser['username']);
        $this->assertSame('test@example.com', $createdUser['email']);

        // To make sure we did NOT store the plain-text password.
        $this->assertTrue(
            password_verify('Password123!', $createdUser['password'])
        );

        // Clean up the test user.
        $delete = $this->db->prepare(
            'DELETE FROM users WHERE id = :id'
        );

        $delete->execute(['id' => $userId]);
    }

    public function testUserCanBeFoundByEmail(): void
    {
        $user = new User($this->db);

        $userId = $user->create(
            'findme',
            'findme@example.com',
            'Password123!'
        );

        $foundUser = $user->findByEmail('findme@example.com');

        $this->assertIsArray($foundUser);
        $this->assertSame($userId, (int) $foundUser['id']);
        $this->assertSame('findme', $foundUser['username']);
        $this->assertSame('findme@example.com', $foundUser['email']);

        //Clean Up
        $delete = $this->db->prepare(
            'DELETE FROM users WHERE id = :id'
        );

        $delete->execute(['id' => $userId]);
    }
}