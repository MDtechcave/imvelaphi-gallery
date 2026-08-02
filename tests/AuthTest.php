<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Auth;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = [];
    }

    protected function tearDown(): void 
    {
        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
    
    public function testUserCanBeLoggedIn(): void
    {

        $auth = new Auth();

        $auth->login(123);

        $this->assertSame(123, $_SESSION['user_id']);
        $this->assertTrue($auth->isLoggedIn());
    }

    public function testUserCanBeLoggedOut(): void
    {

        $auth = new Auth();

        $auth->login(123);
        $auth->logout();

        $this->assertFalse($auth->isLoggedIn());
        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }
}