<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
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
        foreach ($this->testUserIds as $userId) {
            $stmt = $this->db->prepare(
                'DELETE FROM users WHERE id = :id'
            );

            $stmt->execute(['id' => $userId]);
        }

    }


//TESTING IF USER CAN BE CREATED    
public function testUserCanBeCreated(): void
    {
        $user = new User($this->db);

        $userId = $user->create(
            'testuser',
            'test@example.com',
            'Password123!'
        );

        $this->testUserIds[] = $userId;

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
    }

 //TESTING IF USER CAN BE FOUND BY THEIR EMAIL   
public function testUserCanBeFoundByEmail(): void
    {
        $user = new User($this->db);

        $userId = $user->create(
            'findme',
            'findme@example.com',
            'Password123!'
        );

    $this->testUserIds[] = $userId;

        $foundUser = $user->findByEmail('findme@example.com');

        $this->assertIsArray($foundUser);
        $this->assertSame($userId, (int) $foundUser['id']);
        $this->assertSame('findme', $foundUser['username']);
        $this->assertSame('findme@example.com', $foundUser['email']);
    }


//TESTING AUTHENTICATION WITH A NON-EXISTENT EMAIL
public function testUserCannotAuthenticateWithNonExistentEmail(): void 
{
    $user = new User($this->db);

    $authenticatedUser = $user->authenticate(
        'doesnotexist@example.com',
        'Password123!'
    );

    $this->assertNull($authenticatedUser);
}

//TESTING AUTHENTICATION WITH WRONG PASSWORD
public function testUsercanAuthenticateWithCorrectPassword(): void
{
    $user = new User($this->db);

    $userId = $user->create(
        'loginuser',
        'login@example.com',
        'Password123!'
    );

$this->testUserIds[] = $userId;

$authenticatedUser = $user->authenticate(
    'login@example.com',
    'Password123!'
);

$this->assertIsArray($authenticatedUser);
$this->assertSame($userId, (int) $authenticatedUser['id']);
$this->assertSame('loginuser', $authenticatedUser['username']);

}

//TESTING AUTHENTICATION WIT WRONG PASSWORD
public function testUserCannotAuthenticateWithWrongPassword(): void
{
    $user = new User($this->db);

    $userId = $user->create(
        'wrongpass',
        'wrongpass@example.com',
        'Password123!'
    );

$this->testUserIds[] = $userId;

$authenticatedUser = $user->authenticate(
    'wrongpass@example.com',
    'WrongPassword!'
);

$this->assertNull($authenticatedUser);
}
}