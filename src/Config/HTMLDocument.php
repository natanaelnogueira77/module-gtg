<?php 

namespace Config;

final class HTMLDocument 
{
    private static $loadedStyles = [];
    private static $loadedScripts = [];

    public static function addLoadedStyle(string $relativePath): string 
    {
        if(in_array($relativePath, self::$loadedStyles)) {
            return '';
        }

        self::$loadedStyles[] = $relativePath;
        return '<link rel="stylesheet" href="' . $relativePath . '">';
    }

    public static function addLoadedScript(string $relativePath): string 
    {
        if(in_array($relativePath, self::$loadedScripts)) {
            return '';
        }

        self::$loadedScripts[] = $relativePath;
        return '<script src="' . $relativePath . '"></script>';
    }
}