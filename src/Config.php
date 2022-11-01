<?php

define('MAIL', parse_ini_file(realpath(dirname(__FILE__) . '/smtp.ini')));
define('MAIN', parse_ini_file(realpath(dirname(__FILE__) . '/main.ini')));

define('PATH', realpath(dirname(__DIR__)));
define("SYS_VERSION", MAIN['version']);
define('ROOT', MAIN['root']);
define('SITE', MAIN['sitename']);
define('SESS_NAME', MAIN['sessname']);

define('DATA_LAYER', parse_ini_file(realpath(dirname(__FILE__) . '/env.ini')) + [
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

function url(string $uri = null): string 
{
    if($uri) {
        if(strpos($uri, 'http://') !== false || strpos($uri, 'https://') !== false) {
            return $uri;
        }

        return ROOT . "/{$uri}";
    }

    return ROOT;
}

function message(string $message, string $type): array 
{
    return ['message' => $message, 'type' => $type];
}