<?php 

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Achievement;
use PHPUnit\Framework\TestCase;

class AchievementTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testAchievementIds = [];

protected function setUp(): void
{
    $database = new Database();
    $this->db = $database->connect();
}
protected function tearDown(): void
{
    $stmt = $this->db->prepare(
        'DELETE FROM user_achievements WHERE id = :id'
    );
    foreach($this->testAchievementIds as $achievementId) {
        $stmt->execute(['id' => $achievementId]);
    }
    //-----------------------------------------------------

    $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );
    foreach($this->testUserIds as $userId) {
        $stmt->execute (['id' => $userId]);
    }

}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function testIfUserAchievementsCanBeCreated(): void
{
    $user = new User($this->db);

    $userId = $user->create(
        'ZolaniMahola',
        'ZolaniMahola@example.com',
        'NOMVULA WAM'
    );

    $this->testUserIds[] = $userId;
    //------------------------------------------

    $achievement = new Achievement($this->db);

    $achievementId = $achievement->create(
        $userId,
        'storyteller'
    );

    $this->testAchievementIds[] = $achievementId;

    //QUERY THE DATABASE 
$stmt = $this->db->prepare(
    'SELECT user_id, badge_type
    FROM user_achievements
    WHERE id = :id'
);

$stmt->execute(['id' => $achievementId]);

//ASSERTION

$createdAchievement = $stmt->fetch();

$this->assertIsArray($createdAchievement);
$this->assertSame($userId, (int) $createdAchievement['user_id']);
$this->assertSame('storyteller',$createdAchievement['badge_type']);
}
}