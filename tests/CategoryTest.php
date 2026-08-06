<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use Mihledudumashe\ImvelaphiGallery\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private \PDO $db;
    private array $testCategoryIds = [];

protected function setUp(): void
    {
        $database = new Database();
        $this->db = $database->connect();
    }

protected function tearDown(): void
{
    $stmt = $this->db->prepare(
        'DELETE FROM categories WHERE id = :id'
    );
    foreach ($this->testCategoryIds as $categoryId) {
        $stmt->execute (['id' => $categoryId]);
    }
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function testCategoryCanBeCreated(): void
{
    $category = new Category($this->db);

    $categoryId = $category->create(
        'Clothing',
        'Shirt'
    );

    $this->testCategoryIds[] = $categoryId;

    $this->assertIsInt($categoryId);
    $this->assertGreaterThan(0, $categoryId);

    //QUERY THE DATABSE TO CHECK IF CATEGORY CAN BE CREATED

    $stmt = $this->db->prepare(
        'SELECT name, icon
        FROM categories
        WHERE id = :id'
    );

    $stmt->execute(['id' => $categoryId]);

    $createdCategory = $stmt->fetch();

    $this->assertIsArray($createdCategory);
    $this->assertSame('Clothing', $createdCategory['icon']);
    $this->assertSame('Shirt', $createdCategory['name']);
    
}

}