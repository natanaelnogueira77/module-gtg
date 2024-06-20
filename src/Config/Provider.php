<?php 

namespace Config; 

final class Provider 
{
    private static array $data = [];

    private function __construct() 
    {}

    public static function add(string $name, mixed $data): void 
    {
        self::$data[$name] = $data;
    }

    public static function remove(string $name): void 
    {
        unset(self::$data[$name]);
    }

    public static function get(string $name): mixed 
    {
        return isset(self::$data[$name]) ? self::$data[$name] : null;
    }
}