<?php

namespace Tests;

use Mihledudumashe\ImvelaphiGallery\Auth;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public function testUserCanBeLoggedIn(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
        session_start();
        }
        $auth = new Auth();

        $auth->login(123);

        $this->assertSame(123, $_SESSION['user_id']);
        $this->assertTrue($auth->isLoggedIn());
    }

    public function testUserCanBeLoggedOut(): void
    {
        if (session_status() === PHP_SESSION_NONE){
        session_start();
        }
        
        $auth = new Auth();

        $auth->login(123);

        $auth->logout();

        $this->assertFalse($auth->isLoggedIn());
        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }
}