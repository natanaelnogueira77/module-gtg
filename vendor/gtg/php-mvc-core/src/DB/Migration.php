<?php 

namespace GTG\MVC\DB;

use GTG\MVC\Application;

abstract class Migration 
{
    abstract public function up(): void;

    abstract public function down(): void;

    protected function exec(string $sql): void  
    {
        Application::$app->db->exec($sql);
    }
}