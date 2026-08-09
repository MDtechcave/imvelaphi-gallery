<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use Mihledudumashe\ImvelaphiGallery\Post;
use Mihledudumashe\ImvelaphiGallery\Category;
use Mihledudumashe\ImvelaphiGallery\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testCultureIds = [];
    private array $testCommentIds = [];
    private array $testCategoryIds =[];
    private array $testTribeIds = [];
    private array $testPostIds = [];

    protected function setUp(): void
    {
        $database = new Database();
        $this->db = $database->connect();
    }


protected function tearDown(): void
{

     $stmt = $this->db->prepare(
        'DELETE FROM comments WHERE id = :id'
    );

    foreach($this->testCommentIds as $commentId) {
        $stmt->execute(['id' => $commentId]);
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

   $stmt = $this->db->prepare(
        'DELETE FROM posts WHERE id = :id'
    );

    foreach($this->testPostIds as $postId) {
        $stmt->execute(['id' => $postId]);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

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

    foreach ($this->testCultureIds as $cultureId) {
        $stmt->execute(['id' => $cultureId]);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     $stmt = $this->db->prepare(
        'DELETE FROM categories WHERE id = :id'
    );
    foreach($this->testCategoryIds as $categoryId) {
        $stmt->execute(['id' => $categoryId]);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );

    foreach ($this->testUserIds as $userId) {
        $stmt->execute(['id' => $userId]);
    }

}

//TESTING IF A COMMENTS CAN BE CREATED
public function testCommentCanBeCreated(): void
{
     $user = new User($this->db);

    $userId = $user->create(
        'sisonketest',
        'sisonketest@example.com',
        'Samora123!'
    );

    $this->testUserIds[] = $userId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $category = new Category($this->db);

    $categoryId = $category->create(
        'Food',
        'Amagwinya'
    );

    $this->testCategoryIds[] = $categoryId;

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
    );

    $this->testCultureIds[] = $cultureId;

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'Hlubi'
    );

    $this->testTribeIds[] = $tribeId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $post = new Post($this->db);

    $postId = $post->create(
        $userId,
        $cultureId,
        $tribeId,
        $categoryId,
        'Umbacho',
        'Worn during ceremonies',
        'https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg'
    );

    $this->testPostIds[] = $postId;

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $comment = new Comment($this->db);

    $commentId = $comment->create(
        $userId,
        $postId,
        'Thats such an interesting point, thanks for sharing'
    );

    $this->testCommentIds[] = $commentId;
   
//QUERY THE DATABASE FOR THE COMMENT THAT WAS CREATED

$stmt = $this->db->prepare(
    'SELECT user_id, post_id, comment
    FROM comments
    WHERE id = :id'
);

$stmt->execute(['id' => $commentId]);

$createdComment = $stmt->fetch();

$this->assertIsArray($createdComment);
$this->assertSame($userId, (int) $createdComment['user_id']);
$this->assertSame($postId, (int) $createdComment['post_id']);
$this->assertSame( 'Thats such an interesting point, thanks for sharing', $createdComment['comment']);

}
}