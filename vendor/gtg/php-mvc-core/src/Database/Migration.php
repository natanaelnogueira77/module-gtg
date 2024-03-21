<?php 

namespace GTG\MVC\Database;

use GTG\MVC\Application;

abstract class Migration 
{
    protected Database $database;
    
    abstract public function up(): void;
    abstract public function down(): void;

    public function __construct() 
    {
        $this->database = Application::getInstance()->database;
    }

    protected function exec(string $sql): void  
    {
        $this->database->exec($sql);
    }
}