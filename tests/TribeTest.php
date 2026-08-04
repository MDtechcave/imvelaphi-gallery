<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\Culture;
use Mihledudumashe\ImvelaphiGallery\Tribe;
use PHPUnit\Framework\TestCase;

class TribeTest extends TestCase

{
    private \PDO $db;
    private array $testTribeIds = [];
    private array $testCultureIds = [];
//SETUP FUNCTION TO CREATE A DATABASE CONNECTION BEFORE EACH TEST
    protected function setUp(): void
{
    $database = new Database();
    $this->db = $database->connect();
}
//TEARDOWN FUNCTION TO DELETE TEST DATA AFTER EACH TEST
protected function tearDown(): void
{
    $stmt = $this->db->prepare(
        'DELETE FROM tribes WHERE id = :id'
    );

    foreach ($this->testTribeIds as $tribeId) {
        $stmt->execute(['id' => $tribeId]);    
    }

    $stmt = $this->db->prepare(
        'DELETE FROM cultures WHERE id = :id;'
    );

    foreach ($this->testCultureIds as $cultureId) {
        $stmt->execute(['id' => $cultureId]);
    }

}

//TESTING IF TRIBE CAN BE CREATED
public function testTribeCanBeCreated(): void
{
    // A tribe depends on an existing culture, so create one first.
    $culture = new Culture($this->db);

    $cultureId = $culture->create(
        'Xhosa',
    );

    $this->testCultureIds[] = $cultureId;

    $this->assertIsInt($cultureId);
    $this->assertGreaterThan(0, $cultureId);

    //NOW WE CAN CREATE A TRIBE TEST
    $tribe = new Tribe($this->db);

    $tribeId = $tribe->create(
        $cultureId,
        'AmaMpondomise'
    );

    $this->testTribeIds[] = $tribeId;

    //QUERY THE DATABASE TO CHECK IF THE TRIBE WAS CREATED
    $stmt = $this->db->prepare(
        'SELECT culture_id, name
        FROM tribes 
        WHERE id = :id'
    );

    $stmt->execute(['id' => $tribeId]);

    $this->assertIsInt($tribeId);
    $this->assertGreaterThan(0, $tribeId);

    $createdTribe = $stmt->fetch();

    $this->assertIsArray($createdTribe);
    $this->assertSame('AmaMpondomise', $createdTribe['name']);
    $this->assertSame($cultureId, (int) $createdTribe['culture_id']);
}
}