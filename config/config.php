<?php

define('ENV', parse_ini_file(realpath(dirname(__FILE__, 2) . '/env.ini')));

define('APP_VERSION', ENV['app_version']);
define('APP_URL', ENV['app_url']);
define('ROOT', ENV['app_url']);
define('SITE', ENV['app_name']);
define('ERROR_MAIL', ENV['app_error_mail']);

define('SESSION_INFO', [
    'auth_key' => ENV['session_auth'],
    'lang_key' => ENV['session_lang'],
    'flash_key' => ENV['session_flash']
]);

define('DB_INFO', [
    'pdo' => [
        'driver' => ENV['db_driver'],
        'dbname' => ENV['db_name'],
        'host' => ENV['db_host'],
        'port' => ENV['db_port'],
        'username' => ENV['db_username'],
        'passwd' => ENV['db_password'],
        'options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_CASE => PDO::CASE_NATURAL
        ]
    ],
    'migrations' => ['path' => 'src/Database/Migrations', 'namespace' => 'Src\Database\Migrations'],
    'seeders' => ['path' => 'src/Database/Seeders', 'namespace' => 'Src\Database\Seeders'],
    'factories' => ['path' => 'src/Database/Migrations', 'namespace' => 'Src\Database\Migrations']
]);

define('SMTP_INFO', [
    'host' => ENV['smtp_host'],
    'port' => ENV['smtp_port'],
    'username' => ENV['smtp_username'],
    'password' => ENV['smtp_password'],
    'name' => ENV['smtp_name'],
    'email' => ENV['smtp_email'],
    'limit' => ENV['smtp_limit']
]);

define('RECAPTCHA', [
    'site_key' => ENV['recaptcha_site_key'],
    'secret_key' => ENV['recaptcha_secret_key']
]);

define('FACEBOOK', [
    'app_id' => ENV['facebook_id'],
    'app_secret' => ENV['facebook_secret'],
    'app_version' => ENV['facebook_version']
]);

define('GOOGLE', [
    'app_id' => ENV['google_id'],
    'app_secret' => ENV['google_secret']
]);

ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
ini_set('ignore_repeated_source', true);
ini_set('log_errors', true);
error_reporting( E_ALL );

set_error_handler(array('GTG\MVC\Exceptions\ErrorHandler', 'control'), E_ALL);
register_shutdown_function(array('GTG\MVC\Exceptions\ErrorHandler', 'shutdown'));

require_once(realpath(dirname(__FILE__) . '/date_utils.php'));
require_once(realpath(dirname(__FILE__) . '/utils.php'));