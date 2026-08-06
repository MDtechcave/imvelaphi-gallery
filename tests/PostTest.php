<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use Mihledudumashe\ImvelaphiGallery\Category;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testPostIds = [];
    private array $testCultureIds = [];
    private array $testTribeIds = [];
    private array $testCategoryIds = [];

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
        'DELETE FROM category WHERE id = :id'
      );

      foreach ($this->testCategoryIds as $categoryId) {
      $stmt->execute(['id' => $categoryId]);
      }

     $stmt = $this->db->prepare(
        'DELETE FROM tribes WHERE id = :id'
     );

      foreach ($this->testTribeIds as $tribeId){
        $stmt->execute(['id' => $tribeId]);
      }

      $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id'
      );

      foreach ($this->testCultureIds as $cultureId){
        $stmt->execute(['id' => $cultureId]);
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

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
    );

    $this->testCultureIds[] = $cultureId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'AmaMpondomise',
    );

    $this->testTribeIds[] = $tribeId;

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $category = new Category($this->db);

    $categoryId = $category->create(
        'name',
        'icon'
    );

    $this->testCategoryIds[] = $categoryId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $post = new Post($this->db);

    $postId = $post->create(
        $userId,
        $cultureId,
        $tribeId,
        $categoryId,
        'Umbacho',
        'Worn during ceremonies',
        'https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg',
    );

    $this->testPostIds[] = $postId;

   //QUERY DATABASE FOR THE POST I JUST CREATED

   $stmt = $this->db->prepare(
    'SELECT user_id, tribe_id, culture_id, category_id, title, description, image
    FROM posts
    WHERE id = :id'
   );

   $stmt->execute(['id' => $postId]);

   $createdPost = $stmt->fetch();

    $this->assertIsArray($createdPost);
    $this->assertSame($userId, (int) $createdPost['user_id']);
    $this->assertSame($tribeId, (int) $createdPost['tribe_id']);
    $this->assertSame($cultureId, (int) $createdPost['culture_id']);
    $this->assertSame($categoryId, (int) $categoryPost['category_id']);
    $this->assertSame('Umbacho', $createdPost['title']);
    $this->assertSame('Worn during ceremonies', $createdPost['description']);
    $this->assertSame('https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg', $createdPost['image']);
  
}

}