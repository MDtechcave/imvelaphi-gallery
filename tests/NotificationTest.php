<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\User;
use Mihledudumashe\ImvelaphiGallery\Post;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use Mihledudumashe\ImvelaphiGallery\Category;
use Mihledudumashe\ImvelaphiGallery\Notification;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    private \PDO $db;
    private array $testUserIds = [];
    private array $testRelatedUserIds = [];
    private array $testPostIds = [];
    private array $testCultureIds = [];
    private array $testTribeIds = [];
    private array $testCategoryIds = [];
    private array $testNotificationIds = [];

protected function setUp(): void
{
    $database = new Database();
    $this->db = $database->connect();
}
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
protected function tearDown(): void
{
    $stmt = $this->db->prepare(
    'DELETE FROM notifications WHERE id = :id'
    );
    foreach ($this->testNotificationIds as $notificationId) {
        $stmt->execute(['id' => $notificationId]);
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

     $stmt = $this->db->prepare(
        'DELETE FROM posts WHERE id = :id'
    );

    foreach ($this->testPostIds as $postId) {
        $stmt->execute(['id' => $postId]);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

     $stmt =$this->db->prepare(
        'DELETE FROM tribes WHERE id = :id'
    );

    foreach ($this->testTribeIds as $tribeId) {
        $stmt->execute(['id' => $tribeId]);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id'
    );
    foreach ($this->testCultureIds as $cultureId) {
        $stmt->execute(['id' => $cultureId]);
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $stmt = $this->db->prepare(
        'DELETE FROM categories WHERE id = :id'
    );
    foreach ($this->testCategoryIds as $categoryId) {
        $stmt->execute (['id' => $categoryId]);
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );

    foreach ($this->testRelatedUserIds as $relatedUserId) {
        $stmt->execute(['id' => $relatedUserId]);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

     $stmt = $this->db->prepare(
        'DELETE FROM users WHERE id = :id'
    );
    foreach ($this->testUserIds as $userId) {
        $stmt->execute(['id' => $userId]);
    }
    
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function testIfNotificationCanBeCreated(): void 
{
    $user = new User($this->db);

    $userId = $user->create(
        'aviwetest',
        'aviwetest@example.com',
        'Password123!'
    );

    $this->testUserIds[] = $userId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $relatedUser = new User($this->db);

    $relatedUserId = $relatedUser->create(
        'mikey',
        'mikey@example.com',
        'Password123!'
    );

    $this->testRelatedUserIds[] = $relatedUserId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $category = new Category($this->db);

    $categoryId = $category->create(
        'Food',
        'ulusu'
    );

    $this->testCategoryIds[] = $categoryId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa'
    );

    $this->testCultureIds[] = $cultureId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'AmaBhaca'
    );

    $this->testTribeIds[] = $tribeId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $post = new Post($this->db);

    $postId = $post->create(
        $userId,
        $cultureId,
        $tribeId,
        $categoryId,
        'Umbacho',
        'Worn during ceremonies',
        'https://i.pinimg.com/1200x/ae/9b/69/ae9b69b352443ceccff3dab70fbe8ef4.jpg'    );

    $this->testPostIds[] = $postId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $notification = new Notification($this->db);
    
    $notificationId = $notification->create(
        $userId,
        $relatedUserId,
        $postId,
        'like',
        false
    );

    $this->testNotificationIds[] = $notificationId;
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    //QUERY THE DATABASE IF A NOTIFICATION HAS BEEN CREATED

    $stmt = $this->db->prepare(
        'SELECT user_id, type, related_user_id, post_id, is_read
        FROM notifications
        WHERE id = :id'
    );

    $stmt->execute(['id' => $notificationId]);

    //ASSERTION

    $createdNotification = $stmt->fetch();

    $this->assertIsArray($createdNotification);
    $this->assertSame($userId, (int) $createdNotification['user_id']);
    $this->assertSame($relatedUserId, (int) $createdNotification['related_user_id']);
    $this->assertSame($postId, (int) $createdNotification['post_id']);
    $this->assertSame('like', $createdNotification['type']);
    $this->assertSame(0, (int) $createdNotification['is_read']);
}

 }
