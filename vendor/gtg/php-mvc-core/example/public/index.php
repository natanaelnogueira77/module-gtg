<?php

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'on' && $_SERVER['HTTP_HOST'] != 'localhost') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

if(file_exists($maintenance = __DIR__ . '/../maintenance.php')) {
    require $maintenance;
}

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../config/app.php';

$app->router->namespace('Src\App\Controllers\Auth');

$app->router->group(null);
$app->router->get('/', 'AuthController:index', 'home.index', \Src\App\Middlewares\GuestMiddleware::class);
$app->router->post('/', 'AuthController:index', 'home.index', \Src\App\Middlewares\GuestMiddleware::class);

$app->router->group('entrar', \Src\App\Middlewares\GuestMiddleware::class);
$app->router->get('/', 'AuthController:index', 'auth.index');
$app->router->post('/', 'AuthController:index', 'auth.index');

$app->router->group('redefinir-senha', \Src\App\Middlewares\GuestMiddleware::class);
$app->router->get('/', 'ResetPasswordController:index', 'resetPassword.index');
$app->router->post('/', 'ResetPasswordController:index', 'resetPassword.index');
$app->router->get('/{code}', 'ResetPasswordController:verify', 'resetPassword.verify');
$app->router->post('/{code}', 'ResetPasswordController:verify', 'resetPassword.verify');

$app->router->group('logout', \Src\App\Middlewares\UserMiddleware::class);
$app->router->get('/', 'AuthController:logout', 'auth.logout');

$app->router->namespace('Src\App\Controllers\Web');

$app->router->group('contato');
$app->router->get('/', 'ContactController:index', 'contact.index');
$app->router->post('/', 'ContactController:index', 'contact.index');

$app->router->namespace('Src\App\Controllers\User');

$app->router->group('u', \Src\App\Middlewares\UserMiddleware::class);
$app->router->get('/', 'UserController:index', 'user.index');

$app->run();