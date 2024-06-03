<?php

namespace Src\Config;

use Exception;
use Src\Exceptions\ApplicationException;

final class FileSystem 
{
    public const LOCAL_STORAGE = 'local';

    private static string $storageDriver = 'local';

    public static function setStorageDriver(string $storageDriver): void 
    {
        self::$storageDriver = $storageDriver;
    }

    public static function listFiles(string $filepath): array
    {
        try {
            $storage = self::getLocalStorage();
            $files = $storage->listFiles($filepath);

            return $files;
        } catch(Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
    }

    private static function getLocalStorage(): LocalStorage 
    {
        return new LocalStorage();
    }

    public static function uploadFile(string $filepath, string $content): ?array 
    {
        try {
            $storage = self::getLocalStorage();
            $file = $storage->upload($filepath, $content);

            return $file;
        } catch(Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
    }

    public static function deleteFile(string $filepath): bool
    {
        try {
            $isDeleted = false;
            $storage = self::getLocalStorage();
            $storage->delete($filepath);
            $isDeleted = true;

            return $isDeleted;
        } catch(Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
    }

    public static function getLink(string $relativePath): string 
    {
        try {
            $storage = self::getLocalStorage();
            $link = $storage->getLink($relativePath);

            return $link;
        } catch(Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
    }
}
