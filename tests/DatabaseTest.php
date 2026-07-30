<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testDatabaseConnection(): void
    {
        $database = new Database();

        $connection = $database->connect();

        $this->assertInstanceOf(\PDO::class, $connection);
    }
}