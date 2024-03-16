<?php 

namespace GTG\MVC\Database;

use GTG\MVC\Application;
use GTG\MVC\Database\Database;

abstract class Migration 
{
    abstract public function up(): void;
    abstract public function down(): void;

    protected Database $database;

    public function __construct() 
    {
        $this->database = Application::getInstance()->database;
    }

    protected function exec(string $sql): void  
    {
        $this->database->exec($sql);
    }
}