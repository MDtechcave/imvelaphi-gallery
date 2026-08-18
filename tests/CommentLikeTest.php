<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Comment;
use Mihledudumashe\ImvelaphiGallery\Post;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Category;
use Mihledudumashe\ImvelaphiGallery\CommentLike;
use PHPUnit\Framework\TestCase;

class CommentLikeTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testCommentIds = [];
    private array $testPostIds = [];
    private array $testTribeIds = [];
    private array $testCultureIds = [];
    private array $testCategoryIds = [];
    private array $testCommentLikeIds = [];

protected function setUp(): void
{
    $database = new Database();
    $this->db = $database->connect();
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'
protected function tearDown(): void
{
    $stmt = $this->db->prepare(
        'DELETE FROM comment_likes WHERE id = :id'
    );
    foreach($this->testCommentLikeIds as $commentLikeId) {
        $stmt->execute(['id' => $commentLikeId]);
    }
    //----------------------------------------------------
    $stmt = $this->db->prepare(
        'DELETE FROM comments WHERE id = :id'
    );
    foreach($this->testCommentIds as $commentId) {
        $stmt->execute(['id' => $commentId]);
    }
    //--------------------------------------------
    $stmt = $this->db->prepare(
        'DELETE FROM posts WHERE id = :id'
    );
    foreach($this->testPostIds as $postId) {
        $stmt->execute (['id' => $postId]);
    }
    //--------------------------------------------

    $stmt = $this->db->prepare(
        'DELETE FROM categories WHERE id = :id'
    );
    foreach($this->testCategoryIds as $categoryId) {
        $stmt->execute(['id' => $categoryId]);
    }
    //--------------------------------------------
    $stmt = $this->db->prepare(
        'DELETE FROM tribes WHERE id = :id'
    );
    foreach($this->testTribeIds as $tribeId) {
        $stmt->execute(['id' => $tribeId]);
    }
    //--------------------------------------------
    $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id'
    );
    foreach($this->testCultureIds as $cultureId) {
        $stmt->execute(['id' => $cultureId]);
    }
    //--------------------------------------------

    $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );
    foreach ($this->testUserIds as $userId) {
        $stmt->execute (['id' => $userId]);
    }
    //--------------------------------------------
}

public function testIfUserCanLikeAComment(): void
{
    $user = new User($this->db);
    $userAId = $user->create(
        'Mihle.dudumashe',
        'mihle.dudumashe@example.com',
        'PASSWORD123!'
    );

    $this->testUserIds[] = $userAId;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   $user = new User($this->db);

   $userBId = $user->create(
    'Aviwe.dudumashe',
    'aviwe.dudumashe@example.com',
    'PASSWORD123!'
   );

   $this->testUserIds[] = $userBId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
    );

    $this->testCultureIds[] = $cultureId;
//-------------------------------------------------

    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'hlubi'
    );

    $this->testTribeIds[] = $tribeId;
//------------------------------------------------

    $category = new Category($this->db);

    $categoryId = $category->create(
        'Music',
        'Maskandi'
    );

    $this->testCategoryIds[] = $categoryId;
//-------------------------------------------------

    $post = new Post($this->db);

    $postId = $post->create(
        $userAId,
        $cultureId,
        $tribeId,
        $categoryId,
        'Umbacho',
        'Worn During ceremonies',
        'https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg'

    );
    $this->testPostIds[] = $postId;
//-------------------------------------------------------------
    $comment = new Comment($this->db);

    $commentId = $comment->create(
        $userAId,
        $postId,
        'Amazing'
    );

    $this->testCommentIds[] = $commentId;
//------------------------------------------------------------

    $commentLike = new CommentLike($this->db);

    $commentLikeId = $commentLike->create(
        $commentId,
        $userBId
    );

    $this->testCommentLikeIds[] = $commentLikeId;

//  QUERY THE DATABASE

$stmt = $this->db->prepare(
    'SELECT user_id, comment_id
    FROM comment_likes
    WHERE id = :id'
);

$stmt->execute(['id' => $commentLikeId]);

$createdCommentLike = $stmt->fetch();

$this->assertIsArray($createdCommentLike);
$this->assertSame($userBId, (int) $createdCommentLike['user_id']);
$this->assertSame($commentId, (int) $createdCommentLike['comment_id']);
}

//Unlike comment function

public function testIfUserCanUnlikeAComment(): void
{
    $user = new User($this->db);

    // User A - comment owner
    $userAId = $user->create(
        'Mihle.dudumashe',
        'mihle.dudumashe@example.com',
        'PASSWORD123!'
    );

    $this->testUserIds[] = $userAId;


    // User B - person liking/unliking
    $userBId = $user->create(
        'Aviwe.dudumashe',
        'aviwe.dudumashe@example.com',
        'PASSWORD123!'
    );

    $this->testUserIds[] = $userBId;

    //-----------------------------------------------

    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
        );

    $this->testCultureIds[] = $cultureId;
    //----------------------------------------------

    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'hlubi'
        );

    $this->testTribeIds[] = $tribeId;
    //--------------------------------------------

    $category = new Category($this->db);

    $categoryId = $category->create(
        'Music',
        'Maskandi'
    );

    $this->testCategoryIds[] = $categoryId;
    //------------------------------------------------

    $post = new Post($this->db);

    $postId = $post->create(
        $userAId,
        $cultureId,
        $tribeId,
        $categoryId,
        'Umbacho',
        'Worn During ceremonies',
        'image-url'
    );

    $this->testPostIds[] = $postId;
    //------------------------------------------------

    $comment = new Comment($this->db);

    $commentId = $comment->create(
        $userAId,
        $postId,
        'Amazing'
    );

    $this->testCommentIds[] = $commentId;
    //---------------------------------------------------

    $commentLike = new CommentLike($this->db);

    $commentLikeId = $commentLike->create(
        $commentId,
        $userBId
    );

    $this->testCommentLikeIds[] = $commentLikeId;
    //---------------------------------------------------
    $this->assertTrue($commentLike->exists($commentId, $userBId));
    $result = $commentLike->delete($commentId,$userBId);
    $this->assertTrue($result);
    $this->assertFalse($commentLike->exists($commentId, $userBId));
}



}