<?php

define('ENV', parse_ini_file(realpath(dirname(__FILE__, 2) . '/env.ini')));

define('PATH', realpath(dirname(__DIR__)));
define('APP_VERSION', ENV['app_version']);
define('ROOT', ENV['app_url']);
define('SITE', ENV['app_name']);
define('SESS_NAME', ENV['app_sessname']);
define('ERROR_MAIL', ENV['app_error_mail']);

define('MAIL', [
    'host' => ENV['mail_host'],
    'port' => ENV['mail_port'],
    'username' => ENV['mail_username'],
    'password' => ENV['mail_password'],
    'name' => ENV['mail_name'],
    'email' => ENV['mail_email'],
    'limit' => ENV['mail_limit']
]);

define('RECAPTCHA', [
    'host' => ENV['recaptcha_host'],
    'site_key' => ENV['recaptcha_site_key'],
    'secret_key' => ENV['recaptcha_secret_key']
]);

define('DB_INFO', [
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
]);

define('FACEBOOK', [
    'app_id' => '1222448374912330',
    'app_secret' => '001953934a26df57f2fa5443eeefc4d4',
    'app_redirect' => ROOT . '/entrar',
    'app_version' => 'v4.0'
]);

define('GOOGLE', [
    'app_id' => '365252850591-8baeqgcol1s3fjdin2je2q7bmk4s9o4k.apps.googleusercontent.com',
    'app_secret' => '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',
    'app_redirect' => ROOT . '/entrar'
]);