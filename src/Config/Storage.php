<?php 

namespace Src\Config;

use Src\Models\AR\User;

final class Storage 
{
    const STORAGE_PATH = 'public/storage';
    const USERS_STORAGE_FOLDER = 'users';

    public static function getStorageFolder(User $user): string 
    {
        return self::getUserStorageFolder($user);
    }

    public static function getStorageURL(): string 
    {
        return url(self::STORAGE_PATH);
    }

    public static function getUsersStorageURL(): string 
    {
        return self::getStorageURL() . '/' . self::USERS_STORAGE_FOLDER;
    }
    
    public static function getUserStorageURL(User $user): string 
    {
        return self::getUsersStorageURL() . '/user' . $user->id;
    }

    public static function getUserStorageFolder(User $user): string 
    {
        return self::USERS_STORAGE_FOLDER . '/user' . $user->id;
    }
}