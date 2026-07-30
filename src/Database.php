<?php

namespace Mihledudumashe\ImvelaphiGallery;

USE PDO;
USE Dotenv\Dotenv;

class Database
{

     public function connect(): PDO
     {
       $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
       $dotenv->load();

       $host = $_ENV['DB_HOST'];
       $database = $_ENV['DB_NAME'];
       $username = $_ENV['DB_USER'];
       $password = $_ENV['DB_PASSWORD'];

       return new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $username,
        $password
       );
     }
}