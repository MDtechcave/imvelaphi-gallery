<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Post;
use Mihledudumashe\ImvelaphiGallery\Category;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use Mihledudumashe\ImvelaphiGallery\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testTagIds = [];
    private array $testPostIds = [];
    private array $testCategoryIds = [];
    private array $testTribeIds = [];
    private array $testCultureIds = [];

protected function setUp(): void
{
    $database = new database();
    $this->db = $database->connect();
    
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

protected function tearDown(): void
{
    $stmt = $this->db->prepare(
        'DELETE FROM post_tags WHERE id = :id'
    );
    foreach($this->testTagIds as $tagId) {
        $stmt->execute(['id' => $tagId]);
    }
    //--------------------------------------------
    $stmt = $this->db->prepare(
        'DELETE FROM posts WHERE id = :id'
    );
    foreach ($this->testPostIds as $postId) {
        $stmt->execute(['id' => $postId]);
    }
    //--------------------------------------------

    $stmt = $this->db->prepare(
        'DELETE FROM tribes WHERE id = :id'
    );
    foreach ($this->testTribeIds as $tribeId) {
        $stmt->execute(['id' => $tribeId]);
    }
    //---------------------------------------------
    $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id'
    );
    foreach ($this->testCultureIds as $cultureId) {
        $stmt->execute(['id' => $cultureId]);
    }
    //---------------------------------------------

    $stmt = $this->db->prepare(
        'DELETE FROM categories WHERE id = :id'
    );
    foreach ($this->testCategoryIds as $categoryId) {
        $stmt->execute(['id' => $categoryId]);
    }
    //---------------------------------------------
    
    $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );
    foreach ($this->testUserIds as $userId){
        $stmt->execute(['id' => $userId]);
    }
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
public function testIfTagsCanBeCreadted(): void
{
     $user = new User($this->db);
    $userId = $user->create(
        'LumkoTest',
        'LumkoTest@example.com',
        'PASSWORD123!'
    );

    $this->testUserIds[] = $userId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $category = new Category($this->db);

    $categoryId = $category->create(
        'Music',
        'Maskandi'
    );
    $this->testCategoryIds[] = $categoryId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
    );

    $this->testCultureIds[] = $cultureId;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'hlubi'
    );

    $this->testTribeIds[] = $tribeId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $post = new Post($this->db);

    $postId = $post->create(
        $userId,
        $cultureId,
        $tribeId,
        $categoryId,
        'Umbacho',
        'Worn During ceremonies',
        'https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg'

    );
    $this->testPostIds[] = $postId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    $tag = new Tag($this->db);

    $tagId = $tag->create(
        $postId,
        'traditional clothing'
    );

    $this->testTagIds[] = $tagId;
   
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// QUERY THE DATABASE

 $stmt = $this->db->prepare(
    'SELECT post_id, tag
    FROM post_tags
    WHERE id = :id'
 );

 $stmt->execute(['id' => $tagId]);

 //ASSERTIONS

 $createdTag = $stmt->fetch();
 
$this->assertIsArray($createdTag);
$this->assertSame($postId, (int) $createdTag['post_id']);
$this->assertSame('traditional clothing', $createdTag['tag']);
 }

}