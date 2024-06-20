<?php 

namespace Utils;

use GTG\MVC\Session;
use Models\AR\User;

final class StorageUtils 
{
    private const USERS_STORAGE_FOLDER = 'users';

    public static function getStorageFolder(Session $session): string 
    {
        $authentication = self::getAuthentication($session);
        return self::getUserStorageRelativePath($authentication);
    }

    public static function getAuthentication(Session $session): ?User 
    {
        return $session->getAuth() ?: null;
    }

    private static function getUserStorageRelativePath(User $user): string 
    {
        return self::USERS_STORAGE_FOLDER . '/user' . $user->id;
    }
}