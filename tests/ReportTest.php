<?php 

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Post;
use Mihledudumashe\ImvelaphiGallery\Category;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Comment;
use Mihledudumashe\ImvelaphiGallery\Report;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testPostIds = [];
    private array $testCultureIds = [];
    private array $testTribeIds = [];
    private array $testCategoryIds = [];
    private array $testCommentIds = [];
    private array $testReportIds = [];

protected function setUp(): void 
{
    $database = new Database();
    $this->db = $database->connect();
}

protected function tearDown(): void
{
    $stmt = $this->db->prepare(
        'DELETE FROM content_reports WHERE id = :id'
    );
    foreach ($this->testReportIds as $reportId) {
        $stmt->execute (['id' => $reportId]);
    }
    //------------------------------------------
    
    $stmt = $this->db->prepare(
        'DELETE FROM  comments WHERE id = :id'
    );
    foreach ($this->testCommentIds as $commentId) {
        $stmt->execute (['id' => $commentId]);
    }
    //----------------------------------------
    
    $stmt = $this->db->prepare(
        'DELETE FROM  posts WHERE id = :id'
    );
    foreach ($this->testPostIds as $postId) {
        $stmt->execute (['id' => $postId]);
    }
    //-----------------------------------------
    
    $stmt = $this->db->prepare(
        'DELETE FROM tribes WHERE id = :id'
    );
    foreach ($this->testTribeIds as $tribeId) {
        $stmt->execute (['id' => $tribeId]);
    }
    //-----------------------------------------
    
    $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id'
    );
    foreach ($this->testCultureIds as $cultureId) {
        $stmt->execute (['id' => $cultureId]);
    }
    //----------------------------------------
    $stmt = $this->db->prepare(
        'DELETE FROM categories WHERE id = :id'
    );
    foreach ($this->testCategoryIds as $categoryId) {
        $stmt->execute (['id' => $categoryId]);
    }
    //----------------------------------------
    $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );
    foreach($this->testUserIds as $userId) {
        $stmt->execute(['id' => $userId]);
    }
    //----------------------------------------
}

public function testIfUserCanReportAPost(): void
{
    $user = new User($this->db);

    $userId = $user->create(
        'mihletest',
        'mihletest@example.com',
        'PASSWORD123!'
    );

    $this->testUserIds[] = $userId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $category = new Category($this->db);

    $categoryId = $category->create(
        'Food',
        'Sammosa'
    );

    $this->testCategoryIds[] = $categoryId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
    );

    $this->testCultureIds[] = $cultureId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'Hlubi'
    );

    $this->testTribeIds[] = $tribeId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    $post = new Post ($this->db);

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
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $report = new Report($this->db);

        $reportId = $report->create(
        $userId,
        'post',
        $postId,
        null,
        null,

       'Inappropriate content'
    );

    $this->testReportIds[] = $reportId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

//Query the database

    $stmt = $this->db->prepare(
    'SELECT reporter_id, content_type, post_id, comment_id, reported_user_id, reason
     FROM content_reports
     WHERE id = :id'
);

$stmt->execute([
    'id' => $reportId
]);

$createdReport = $stmt->fetch();

// Assertions

$this->assertIsArray($createdReport);
$this->assertSame($userId, (int) $createdReport['reporter_id']);
$this->assertSame('post', $createdReport['content_type']);
$this->assertSame($postId, (int) $createdReport['post_id']);
$this->assertNull($createdReport['comment_id']);
$this->assertNull($createdReport['reported_user_id']);
$this->assertSame('Inappropriate content', $createdReport['reason']);
}
}