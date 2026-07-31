<?php

namespace Mihledudumashe\ImvelaphiGallery;

class Auth
{
    public function login(int $userId): void
    {
        $_SESSION['user_id'] = $userId;
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function logout():void
    {
        unset($_SESSION['user_id']);
    }

}