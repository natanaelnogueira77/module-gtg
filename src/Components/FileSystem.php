<?php

namespace Src\Components;

use Exception;
use GTG\MVC\Application;
use Spatie\Dropbox\Client as DropboxClient;
use Src\Components\LocalStorage;

class FileSystem 
{
    const LOCAL_STORAGE = 'local';
    const DROPBOX_STORAGE = 'dropbox';

    private static string $storageDriver = 'local';
    private static ?array $credentials;
    private static ?Exception $error = null;

    public static function setStorageDriver(string $storageDriver): void 
    {
        self::$storageDriver = $storageDriver;
    }

    public static function setCredentials(?array $credentials = null): void 
    {
        self::$credentials = $credentials;
    }

    private static function getDropboxClient(): DropboxClient
    {
        return new DropboxClient([self::$credentials['key'], self::$credentials['secret']]);
    }

    private static function getLocalStorage(): LocalStorage 
    {
        return new LocalStorage();
    }

    public static function listFiles(string $filepath): array
    {
        $files = [];
        try {
            if(self::isLocalStorage()) {
                $storage = self::getLocalStorage();
                $files = $storage->listFiles($filepath);
            } elseif(self::isDropboxStorage()) {
                $client = self::getDropboxClient();
                if($result = $client->listFolder(self::$credentials['folder_root'] . '/' . $filepath)) {
                    foreach($result['entries'] as $index => $file) {
                        $files[] = [
                            'name' => $file['name'],
                            'link' => $client->getTemporaryLink(self::$credentials['folder_root'] . '/' . $filepath . '/' . $file['name']),
                            'path' => $filepath . '/' . $file['name']
                        ];
                    }
                }
            }
        } catch(Exception $e) {
            self::$error = $e;
        }

        return $files;
    }

    public static function uploadFile(string $filepath, string $content): ?array 
    {
        $file = [];
        try {
            if(self::isLocalStorage()) {
                $storage = self::getLocalStorage();
                $file = $storage->upload($filepath, $content);
            } elseif(self::isDropboxStorage()) {
                $client = self::getDropboxClient();
                $client->upload(self::$credentials['folder_root'] . '/' . $filepath, file_get_contents($content), 'add');
                $file = [
                    'name' => array_pop(explode('/', $filepath)),
                    'link' => $client->getTemporaryLink(self::$credentials['folder_root'] . '/' . $filepath),
                    'path' => $filepath
                ];
            }
        } catch(Exception $e) {
            self::$error = $e;
        }

        return $file;
    }

    public static function deleteFile(string $filepath): bool
    {
        $isDeleted = false;
        try {
            if(self::isLocalStorage()) {
                $storage = self::getLocalStorage();
                $storage->delete($filepath);
                $isDeleted = true;
            } elseif(self::isDropboxStorage()) {
                $client = self::getDropboxClient();
                $client->delete(self::$credentials['folder_root'] . '/' . $filepath);
                $isDeleted = true;
            }
        } catch(Exception $e) {
            self::$error = $e;
        }

        return $isDeleted;
    }

    public static function getLink(string $relativePath): string 
    {
        $link = '';
        try {
            if(self::isLocalStorage()) {
                $storage = self::getLocalStorage();
                $link = $storage->getLink($relativePath);
            } elseif(self::isDropboxStorage()) {
                $client = self::getDropboxClient();
                $link = $client->getTemporaryLink(self::$credentials['folder_root'] . '/' . $relativePath);
            }
        } catch(Exception $e) {
            self::$error = $e;
        }

        return $link;
    }

    private static function isLocalStorage(): bool 
    {
        return self::$storageDriver == self::LOCAL_STORAGE;
    }

    private static function isDropboxStorage(): bool 
    {
        return self::$storageDriver == self::DROPBOX_STORAGE;
    }

    public static function error(): ?Exception 
    {
        return self::$error;
    }
}
