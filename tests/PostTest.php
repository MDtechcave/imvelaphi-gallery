<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testPostIds = [];
    protected function setUp(): void
    {
        $database = new Database();
        $this->db = $database->connect();
    }

protected function tearDown(): void
{
    $stmt = $this->db->prepare(
        'DELETE FROM posts WHERE id = :id'
    );

    foreach ($this->testPostIds as $postId) {
            $stmt->execute(['id' => $postId]);
      }  

    $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );

     foreach ($this->testUserIds as $userId) {
        $stmt->execute(['id' => $userId]);
     }

}

//TESTING  IF POST CAN BE CREATED
public function testPostCanBeCreated(): void
{
    $user = new User($this->db);

    $userId = $user->create(
        'mihletestuser',
        'mihletest@example.com',
        'Password123!'
    );

    $this->testUserIds[] = $userId;

    $post = new Post($this->db);

    $postId = $post->create(
        $userId,
        'Umbacho',
        'Worn during ceremonies',
        'https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg',
        $categoryId
    );

    $this->testPostIds[] = $postId;

   //QUERY DATABASE FOR THE POST I JUST CREATED

   $stmt = $this->db->prepare(
    'SELECT user_id, title, description, image, category_id
    FROM posts
    WHERE id = :id'
   );

   $stmt->execute(['id' => $postId]);

   $createdPost = $stmt->fetch();

    $this->assertSame($userId, (int) $createdPost['user_id']);
    $this->assertSame('Umbacho', $createdPost['title']);
    $this->assertSame('Worn during ceremonies', $createdPost['description']);
    $this->assertSame('https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg', $createdPost['image']);
    $this->assertSame($categoryId, $createdPost['category_id']);
  
}

}