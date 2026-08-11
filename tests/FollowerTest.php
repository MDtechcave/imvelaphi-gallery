<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Follower;
use PHPUnit\Framework\TestCase;

class FollowerTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testFollowerIds = [];

protected function setup(): void
{
    $database = new Database();
    $this->db = $database->connect();
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
protected function tearDown(): void
{
    $stmt = $this->db->prepare(
        'DELETE FROM followers WHERE id = :id'
    );
    foreach ($this->testFollowerIds as $followerId) {
        $stmt->execute(['id' => $followerId]);
    }

    $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );
    foreach ($this->testUserIds as $userId) {
        $stmt->execute(['id' => $userId]);
    }
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function testIfFollowersCanBeCreated(): void
{
    $user = new User($this->db);
    $userAId = $user->create(
        'UserAtest',
        'UserAtest@example.com',
        'UserA'
    );

    $this->testUserIds[] = $userAId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $user = new User($this->db);
    $userBId = $user->create(
        'UserBtest',
        'UserBtest@example.com',
        'UserB'
    );

    $this->testUserIds[] = $userBId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $follower = new Follower ($this->db);

    $followerId = $follower->create(
        $userAId,
        $userBId
    );

    $this->testFollowerIds[] = $followerId;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//QUERY THE DATABASE OF COURSE

$stmt = $this->db->prepare(
    'SELECT follower_id, following_id
    FROM followers
    WHERE id = :id'
);

$stmt->execute(['id' => $followerId]);

//ASSERTIONS

$createdFollower = $stmt->fetch();

$this->assertIsArray($createdFollower);
$this->assertSame($userAId, (int) $createdFollower['following_id']);
$this->assertSame($userBId, (int) $createdFollower['follower_id']);    
}















    }
