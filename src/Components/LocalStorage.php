<?php

namespace Src\Components;

use Exception;

class LocalStorage 
{
    const STORAGE_ROOT = 'public/storage';

    public function __construct() 
    {
        
    }

    private function createFolder(string $path): bool 
    {
        return is_dir($path) || mkdir($path);
    }

    public function listFiles(string $filepath): array
    {
        if($files = scandir($this->getLocalStoragePath($filepath))) {
            $files = array_filter($files, function ($e) {
                return !in_array($e, ['.', '..']);
            });

            foreach($files as $index => $file) {
                $files[$index] = [
                    'name' => $file,
                    'link' => $this->getLocalStorageURL($filepath . '/' . $file),
                    'path' => $filepath . '/' . $file
                ];
            }
        }

        return $files;
    }

    public function upload(string $filepath, string $content): ?array 
    {
        $completePath = self::getLocalStoragePath($filepath);
        $path = '';

        $pathParts = explode('/', $completePath);
        $filename = array_pop($pathParts);

        foreach($pathParts as $index => $part) {
            $path .= $part;
            if(!$this->createFolder($path)) {
                throw new Exception(_('Lamentamos, mas não foi possível criar o caminho para o arquivo!'));
            }
            $path .= '/';
        }

        if(!move_uploaded_file($content, $completePath)) {
            throw new Exception(_('Lamentamos, mas parece que ocorreu um erro no upload do arquivo!'));
        }

        return [
            'name' => $filename,
            'link' => self::getLocalStorageURL($filepath),
            'path' => $filepath
        ];
    }

    public function delete(string $filepath): bool
    {
        $files = glob($this->getLocalStoragePath($filepath));
        if(count($files) > 0) {
            foreach($files as $file) {
                if(is_file($file)) {
                    unlink($file);
                }
            }
        }

        return true;
    }

    public function getLink(string $relativePath): string
    {
        return $this->getLocalStorageURL($relativePath);
    }

    private function getLocalStorageURL(string $relativePath): string 
    {
        return url(self::STORAGE_ROOT . '/' . $relativePath);
    }

    private function getLocalStoragePath(string $relativePath): string 
    {
        return dirname(__FILE__, 3) . '/' . self::STORAGE_ROOT . '/' . $relativePath;
    }
}
