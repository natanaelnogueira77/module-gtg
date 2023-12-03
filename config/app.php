<?php 

define('ENV', parse_ini_file(realpath(dirname(__FILE__, 2) . '/env.ini')));

$app = new \GTG\MVC\Application(dirname(__DIR__));
$app->setRouter(ENV['app_url']);
$app->setSessionParams(
    ENV['session_auth'], 
    ENV['session_flash'], 
    ENV['session_lang']
);
$app->setDatabaseConnection(
    ENV['db_driver'], 
    ENV['db_name'], 
    ENV['db_host'], 
    ENV['db_port'], 
    ENV['db_username'], 
    ENV['db_password'],
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
    ENV['smtp_host'],
    ENV['smtp_port'],
    ENV['smtp_username'],
    ENV['smtp_password'],
    ENV['smtp_name'],
    ENV['smtp_email']
);
$app->setViews(__DIR__ . '/../resources/views', 'error/index');
$app->setAppData(require_once __DIR__ . '/app-data.php');
$app->apply();

require_once(realpath(dirname(__FILE__) . '/utils.php'));

return $app;