<?php 

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use GTG\MVC\Application;

$app = new Application(__DIR__, ['db' => DB_INFO]);
$app->db->applyMigrations();