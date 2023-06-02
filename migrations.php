<?php 

require_once __DIR__ . '/vendor/autoload.php';

use GTG\MVC\Application;

define('ENV', parse_ini_file(realpath(dirname(__FILE__) . '/env.ini')));

$app = new Application(__DIR__, [
    'db' => require_once __DIR__ . '/config/database.php'
]);

require_once(realpath(dirname(__FILE__) . '/config/date-utils.php'));
require_once(realpath(dirname(__FILE__) . '/config/utils.php'));

$app->db->applyMigrations();