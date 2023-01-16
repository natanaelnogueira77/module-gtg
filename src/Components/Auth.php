<?php

namespace Src\Components;

use Exception;
use Src\Models\User;

class Auth 
{
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function get() 
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        return isset($_SESSION[SESS_NAME]) ? $_SESSION[SESS_NAME] : null;
    }

    public static function set(User $user): void 
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        $_SESSION[SESS_NAME] = $user;
    }

    public static function destroy(): void 
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        if(isset($_SESSION[SESS_NAME])) {
            unset($_SESSION[SESS_NAME]);
        }
    }
}