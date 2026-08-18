<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Post;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Category;
use Mihledudumashe\ImvelaphiGallery\Comment;
use Mihledudumashe\ImvelaphiGallery\Share;
use PHPUnit\Framework\TestCase;

class ShareTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testPostIds = [];
    private array $testCultureIds = [];
    private array $testTribeIds = [];
    private array $testCategoryIds = [];
    private array $testCommentIds = [];
    private array $testShareIds = [];

protected function setUp(): void
{
    $database = new Database();
    $this->db = $database->connect();
}

protected function tearDown(): void
{
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        $stmt = $this->db->prepare(
            'DELETE FROM shares WHERE id = :id'
        );
        foreach($this->testShareIds as $shareId) {
            $stmt->execute(['id' => $shareId]);
        }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    
    $stmt = $this->db->prepare(
        'DELETE FROM comments WHERE id = :id'
    );
    foreach ($this->testCommentIds as $commentId) {
        $stmt->execute(['id' => $commentId]);
    }
   
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $stmt = $this->db->prepare(
        'DELETE FROM posts WHERE id = :id'
    );
    foreach ($this->testPostIds as $postId) {
        $stmt->execute(['id' => $postId]);
    }
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     $stmt = $this->db->prepare(
        'DELETE FROM tribes WHERE id = :id'
    );
    foreach ($this->testTribeIds as $tribeId) {
        $stmt->execute (['id' => $tribeId]);
    }
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id'
    );
    foreach($this->testCultureIds as $cultureId) {
        $stmt->execute(['id' => $cultureId]);
    }

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    
    $stmt = $this->db->prepare(
        'DELETE FROM categories WHERE id = :id'
    );
    foreach ($this->testCategoryIds as $categoryId) {
        $stmt->execute(['id' => $categoryId]);
    }
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

     $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );
    foreach($this->testUserIds as $userId) {
        $stmt->execute (['id' => $userId]);    
    }
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

//TESTING IF USERS CAN SHARE POSTS FROM USERS
public function testSharesCanBeMade(): void 
{
    $user = new User($this->db);

    $userAId = $user->create(
        'litshepetest',
        'litshepetest@example.com',
        'NATALA'
    );

    $this->testUserIds[] = $userAId;
    //------------------------------------------------

    $user = new User($this->db);

    $userBId = $user->create(
        'SizaTest',
        'SizaTest@example.com',
        'PASSWORD123!'
    );

    $this->testUserIds[] = $userBId;

    //-------------------------------------------------

    $category = new Category($this->db);

    $categoryId = $category->create(
        'Food',
        'Chicken feet'
    );

    $this->testCategoryIds[] = $categoryId;
    //------------------------------------------------

    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
    );

    $this->testCultureIds[] = $cultureId;
    //-----------------------------------------------

    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'Amafengu'
    );
    
    $this->testTribeIds[] = $tribeId;
    //--------------------------------------

    $post = new Post ($this->db);

    $postId = $post->create(
        $userAId,
        $cultureId,
        $tribeId,
        $categoryId,
        'Umbacho',
        'Worn during ceremonies',
        'https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg'
    );

    $this->testPostIds[] = $postId;
    //---------------------------------------------------------------

    $comment = new Comment($this->db);

    $commentId = $comment->create(
        $userAId,
        $postId,
        'Great!'
    );

    $this->testCommentIds[] = $commentId;

    //---------------------------------------------------------

    $share = new Share($this->db);

    $shareId = $share->create(
        $userBId,
        $postId,
        'Internal'
    );

    $this->testShareIds[] = $shareId;

    //QUERY THE DATABASE FOR THE SHARE THAT WAS CREATED

    $stmt = $this->db->prepare(
        'SELECT user_id, post_id, platform
        FROM shares
        WHERE id =:id'
    );

    $stmt->execute(['id' => $shareId]);

    //ASSERTIONS

    $createdShare = $stmt->fetch();

    $this->assertIsArray($createdShare);
    $this->assertSame($userBId, (int) $createdShare['user_id']);
    $this->assertSame($postId, (int) $createdShare['post_id']);
    $this->assertSame('Internal', $createdShare['platform']);
}
}

