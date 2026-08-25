<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Post;
use Mihledudumashe\ImvelaphiGallery\Report;
use Mihledudumashe\ImvelaphiGallery\Category;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use Mihledudumashe\ImvelaphiGallery\Moderator;
use PHPUnit\Framework\TestCase;

class ModeratorTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testPostIds = [];
    private array $testReportIds = [];
    private array $testCategoryIds = [];
    private array $testCultureIds = [];
    private array $testTribeIds = [];

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
    foreach($this->testReportIds as $reportId) {
        $stmt->execute(['id' => $reportId]);
    }
    
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    
    $stmt = $this->db->prepare(
        'DELETE FROM posts WHERE id = :id'
    );
    foreach($this->testPostIds as $postId) {
        $stmt->execute(['id' => $postId]);
    }
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    $stmt = $this->db->prepare(
        'DELETE FROM tribes WHERE id = :id'
    );
    foreach($this->testTribeIds as $tribeId) {
        $stmt->execute(['id' => $tribeId]);
    }

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id'
    );
    foreach($this->testCultureIds as $cultureId) {
        $stmt->execute(['id' => $cultureId]);
    }
    
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $stmt = $this->db->prepare(
        'DELETE FROM categories WHERE id = :id'
    );
    foreach($this->testCategoryIds as $categoryId) {
        $stmt->execute(['id' => $categoryId]);
    }
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );
    foreach($this->testUserIds as $userId) {
        $stmt->execute(['id' => $userId]);
    }
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function testModeratorCanReviewReport(): void
{
    $user = new User($this->db);

    $userId = $user->create(
        'bentest',
        'bentest@example.com',
        'PASSWORD123!'
    );

    $this->testUserIds[] = $userId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $moderator = new User($this->db);

    $moderatorId = $moderator->create(
        'moderatortest',
        'moderatortest@example.com',
        'PASSWORD1231'
    );

    $this->testUserIds[] = $userId;

    $moderator->updateRole($moderatorId, 'moderator');

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $category = new Category($this->db);

    $categoryId = $category->create(
        'stories',
        'Ukutya'
    );

    $this->testCategoryIds[] = $categoryId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
    );

    $this->testCultureIds[] = $cultureId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
    $cultureId,    
    'Hlubi'
    );

    $this->testTribeIds[] = $tribeId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $post = new post($this->db);

    $postId = $post->create(
        $userId,
        $categoryId,
        $cultureId,
        $tribeId,
        'Umbacho',
        'Worn during ceremonies',
        'https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg'  
        
);

    $this->testPostIds[] = $postId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $report = new Report($this->db);

    $reportId = $report->create(
        $userId,
        $postId,
        null,
        null,
        'Inappropriate content',
        'post'
);
    $this->testReportIds[] = $reportId;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $moderator = new Moderator($this->db);

    $moderator->reviewReport(
        $reportId,
        $moderatorId
);

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


//QUERY THE DATABASE
    $stmt = $this->db->prepare(
        'SELECT status, reviewed_by, reviewed_at
        FROM content_reports
        WHERE id = :id'
);

$stmt->execute(['id' => $reportId]);

$moderatedReport = $stmt->fetch();

//ASSERTIONS 
$this->assertIsArray($moderatedReport);
$this->assertSame('reviewed', $moderatedReport['status']);
$this->assertSame($moderatorId,(int) $moderatedReport['reviewed_by']);
$this->assertNotNull($moderatedReport['reviewed_at']);
}
}