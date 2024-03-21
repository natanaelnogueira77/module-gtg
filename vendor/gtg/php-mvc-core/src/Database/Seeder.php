<?php 

namespace GTG\MVC\Database;

use GTG\MVC\Application;

abstract class Seeder 
{
    protected Database $database;
    
    abstract public function run(): void;

    public function __construct() 
    {
        $this->database = Application::getInstance()->database;
    }

    protected function exec(string $sql): void  
    {
        $this->database->exec($sql);
    }
}