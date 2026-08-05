<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use Mihledudumashe\ImvelaphiGallery\Post;
use Mihledudumashe\ImvelaphiGallery\Like;
use PHPUnit\Framework\TestCase;

class LikeTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testCultureIds = [];
    private array $testTribeIds = [];
    private array $testPostIds = [];
    private array $testLikeIds = [];

protected function setUp(): void
{
    $database = new Database();
    $this->db = $database->connect();
}

protected function tearDown(): void
{

     $stmt = $this->db->prepare(
        'DELETE FROM likes WHERE id = :id'
    );

    foreach($this->testLikeIds as $likeId) {
        $stmt->execute(['id' => $likeId]);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $stmt = $this->db->prepare(
        'DELETE FROM posts WHERE id = :id'
    );

    foreach($this->testPostIds as $postId) {
        $stmt->execute(['id' => $postId]);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $stmt = $this->db->prepare(
        'DELETE FROM tribes WHERE id = :id'
    );

    foreach($this->testTribeIds as $tribeId) {
        $stmt->execute(['id' => $tribeId]);
    }
   
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id'
    );

    foreach($this->testCultureIds as $cultureId) {
        $stmt->execute(['id' => $cultureId]);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

     $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );

    foreach($this->testUserIds as $userId) {
        $stmt->execute(['id' => $userId]);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
}

//TESTING IF LIKES CAN BE MADE 
public function testLikeCanBeCreated(): void
{
    $user = new User($this->db);

    $userId = $user->create(
        'mihletest',
        'mihletest@example.com',
        'Password123!'
    );

    $this->testUserIds[] = $userId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
    );

    $this->testCultureIds[] = $cultureId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'AmaMfengu'
    );

    $this->testTribeIds[] = $tribeId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $post = new Post($this->db);

    $postId = $post->create(
        $userId,
        $cultureId,
        $tribeId,
        'Umbhaco',
        'Worn during ceremonies',
        'https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg'
    );

    $this->testPostIds[] = $postId;

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $like = new Like($this->db);

    $likeId = $like->create(
        $userId,
        $postId
    );

    $this->testLikeIds[] = $likeId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//QUERY THE DATABASE IF THE LIKE WAS CREATED
$stmt = $this->db->prepare(
    'SELECT user_id, post_id
    FROM likes
    WHERE id = :id'
);

$stmt->execute(['id' => $likeId]);

$createdLike = $stmt->fetch();

$this->assertIsArray($createdLike);
$this->assertSame($userId, (int) $createdLike['user_id']);
$this->assertSame($postId, (int) $createdLike['post_id']);
}












    
}