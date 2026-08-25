<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];

protected function setUp(): void
{
    $database = new Database();
    $this->db = $database->connect();
}

protected function tearDown(): void
{
    $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );
    foreach ($this->testUserIds as $userId) {
        $stmt->execute(['id' => $userId]);
    }

}

public function testIfUsersRoleDefaultsToUser(): void
{
    $user = new User($this->db);

    $userId = $user->create(
        'SesonaTest',
        'SesonaTest@example.com',
        'PASSWORD123!'
    );

    $this->testUserIds[] = $userId;

//QUERY THE DATABASE
    $stmt = $this->db->prepare(
        'SELECT role
        FROM users
         WHERE id = :id'
    );
$stmt->execute(['id' => $userId]);

//ASSERTION 
$createdUser = $stmt->fetch();

$this->assertIsArray($createdUser);
$this->assertSame('user', $createdUser['role']);

}

public function testUpdateRoleChangesUserToModerator(): void
{
    $user = new User($this->db);

    $userId = $user->create(
        'ModTest',
        'modtest@example.com',
        'PASSWORD123!'
    );

    $this->testUserIds[] = $userId;

    $user->updateRole($userId, 'moderator');

//QUERY THE DATABASE
    $stmt = $this->db->prepare(
        'SELECT role
        FROM users
         WHERE id = :id'
    );
    $stmt->execute(['id' => $userId]);

//ASSERTION
    $updatedUser = $stmt->fetch();

    $this->assertIsArray($updatedUser);
    $this->assertSame('moderator', $updatedUser['role']);
}
}