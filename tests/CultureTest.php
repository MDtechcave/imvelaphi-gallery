<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\Culture;
use PHPUnit\Framework\TestCase;

class CultureTest extends TestCase
{
    private \PDO $db;
    private array $testCultureIds = [];

protected function setUp():  void
    {
        $database = new Database();
        $this->db = $database->connect();
    }

protected function tearDown(): void
{
    $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id'
    );
    foreach ($this->testCultureIds as $cultureId) {
        $stmt->execute(['id' => $cultureId]);
    }

}

//TESTING IF CULTURE CAN BE CREATED
public function testCultureCanBeCreated(): void
{
    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa',
    );

    $this->testCultureIds[] = $cultureId;

    $this->assertIsInt($cultureId);
    $this->assertGreaterThan(0, $cultureId);

    //QUERY THE DATABASE TO CHECK IF THE CULTURE WAS CREATED
    $stmt = $this->db->prepare(
        'SELECT name
        FROM cultures 
        WHERE id = :id'
    );
    $stmt->execute(['id' => $cultureId]);
    $createdCulture = $stmt->fetch();

    $this->assertIsArray($createdCulture);
    $this->assertSame('Xhosa', $createdCulture['name']);

}



}   