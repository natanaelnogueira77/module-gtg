<?php 

$app = new \GTG\MVC\Application(dirname(__DIR__));
$app->setRouter('paste_your_project_url_here/vendor/gtg/php-mvc-core/example');
$app->setSessionParams(
    'auth_key', 
    'flash_key', 
    'lang_key'
);
$app->setDatabaseConnection(
    'mysql', 
    'gtg_php_mvc_core', 
    'localhost', 
    '3306', 
    'root', 
    'root',
    [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
);
$app->setMigrations(
    'src/Database/Migrations', 
    'Src\Database\Migrations'
);
$app->setSeeders(
    'src/Database/Seeders', 
    'Src\Database\Seeders'
);
$app->setSMTP(
    'smtp_host',
    'smtp_port',
    'smtp_username',
    'smtp_password',
    'smtp_name',
    'smtp_email'
);
$app->setViews(
    __DIR__ . '/../resources/views', 
    'error/index'
);
$app->setAppData(require_once __DIR__ . '/app-data.php');
$app->apply();

return $app;