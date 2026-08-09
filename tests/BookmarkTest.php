<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Post;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Category;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use Mihledudumashe\ImvelaphiGallery\Bookmark;
use PHPUnit\Framework\TestCase;

class BookmarkTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testPostIds = [];
    private array $testCultureIds = [];
    private array $testCategoryIds = [];
    private array $testTribeIds = [];
    private array $testBookmarkIds = [];

    //SETUP
protected function setUp(): void
{
    $database = new Database();
    $this->db = $database->connect();
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//TEARDOWN

protected function tearDown(): void
{
      $stmt = $this->db->prepare(
        'DELETE FROM bookmarks WHERE id = :id'
    );

    foreach($this->testBookmarkIds as $bookmarkId) {
        $stmt->execute(['id' => $bookmarkId]);
}
  
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $stmt = $this->db->prepare(
        'DELETE FROM posts WHERE id = :id'
    );

    foreach($this->testPostIds as $postId) {
        $stmt->execute(['id' => $postId]);
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $stmt = $this->db->prepare(
        'DELETE FROM categories WHERE id = :id'
    );

    foreach($this->testCategoryIds as $categoryId) {
        $stmt->execute(['id' => $categoryId]);
    }
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  $stmt = $this->db->prepare(
        'DELETE FROM tribes WHERE id = :id'
    );

    foreach($this->testTribeIds as $tribeId) {
        $stmt->execute(['id' => $tribeId]);
    }
 //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    
      $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id'
    );

    foreach($this->testCultureIds as $cultureId)
 {
    $stmt->execute(['id' => $cultureId]);
 }
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

  $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );

    foreach($this->testUserIds as $userId) {
        $stmt->execute(['id' => $userId]);
}
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

//TESTING IF USERS CAN BOOKMARK POSTS

public function testBookmarkCanBeCreated(): void 
{
    
    $user = new User($this->db);

    $userId = $user->create(
        'mihletest',
        'mihletest@example.com',
        'Password123@!'
    );

    $this->testUserIds[] = $userId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
    );

    $this->testCultureIds[] = $cultureId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~`
    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'Hlubi'  
    );

    $this->testTribeIds[] = $tribeId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $category = new Category ($this->db);

    $categoryId = $category->create(
        'food',
        'amagwinya'
    );

    $this->testCategoryIds[] = $categoryId;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
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
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $bookmark = new Bookmark($this->db);

    $bookmarkId = $bookmark->create(
        $userId,
        $postId
    );

    $this->testBookmarkIds[] = $bookmarkId;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//QUERY THE DATABASE FOR THE BOOKMARK THAT HAS BEEN JUST MADE

    $stmt = $this->db->prepare(
        'SELECT user_id, post_id
        FROM bookmarks
        WHERE id = :id'
    );

    $stmt->execute(['id' => $bookmarkId]);

    $createdBookmark = $stmt->fetch();

    $this->assertIsArray($createdBookmark);
    $this->assertSame($userId, (int) $createdBookmark['user_id']);
    $this->assertSame($postId, (int) $createdBookmark['post_id']);
}
}
