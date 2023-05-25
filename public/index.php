<?php

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'on' && $_SERVER['HTTP_HOST'] != 'localhost') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

$app = new \GTG\MVC\Application(dirname(__DIR__), [
    'projectUrl' => APP_URL,
    'session' => SESSION_INFO,
    'view' => ['path' => __DIR__ . '/../resources/views'],
    'db' => DB_INFO,
    'smtp' => SMTP_INFO
]);

setlocale(LC_ALL, $app->session->getLanguage()[1]);
putenv('LANGUAGE=' . $app->session->getLanguage()[1]);

bindtextdomain('messages', dirname(__FILE__, 2) . '/lang');
bind_textdomain_codeset('messages', 'UTF-8');
textdomain('messages');

date_default_timezone_set('America/Recife');

$app->router->namespace('Src\App\Controllers\Web');

$app->router->group(null);
$app->router->get('/', 'HomeController:index', 'home.index', \Src\App\Middlewares\GuestMiddleware::class);
$app->router->post('/', 'HomeController:index', 'home.index', \Src\App\Middlewares\GuestMiddleware::class);

$app->router->namespace('Src\App\Controllers\Auth');

$app->router->group('entrar', \Src\App\Middlewares\GuestMiddleware::class);
$app->router->get('/', 'AuthController:index', 'auth.index');
$app->router->post('/', 'AuthController:index', 'auth.index');
$app->router->post('/expired', 'AuthController:expired', 'auth.expired');
$app->router->post('/check', 'AuthController:check', 'auth.check');

$app->router->group('redefinir-senha', \Src\App\Middlewares\GuestMiddleware::class);
$app->router->get('/', 'ResetPasswordController:index', 'resetPassword.index');
$app->router->post('/', 'ResetPasswordController:index', 'resetPassword.index');
$app->router->get('/{code}', 'ResetPasswordController:verify', 'resetPassword.verify');
$app->router->post('/{code}', 'ResetPasswordController:verify', 'resetPassword.verify');

$app->router->group('criar-conta', \Src\App\Middlewares\GuestMiddleware::class);
$app->router->get('/', 'RegisterController:index', 'register.index');
$app->router->post('/', 'RegisterController:index', 'register.index');

$app->router->group('logout', \Src\App\Middlewares\UserMiddleware::class);
$app->router->get('/', 'AuthController:logout', 'auth.logout');

$app->router->namespace('Src\App\Controllers');

$app->router->group('erro');
$app->router->get('/{code}', 'ErrorController:index', 'error.index');

$app->router->group('ml');
$app->router->post('/add', 'MediaLibraryController:add', 'mediaLibrary.add');
$app->router->get('/load', 'MediaLibraryController:load', 'mediaLibrary.load');
$app->router->delete('/delete', 'MediaLibraryController:delete', 'mediaLibrary.delete');

$app->router->group('language');
$app->router->get('/{lang}', 'LanguageController:index', 'language.index');

$app->router->namespace('Src\App\Controllers\Admin');

$app->router->group('admin', \Src\App\Middlewares\AdminMiddleware::class);
$app->router->get('/', 'AdminController:index', 'admin.index');
$app->router->put('/system', 'AdminController:system', 'admin.system');

$app->router->group('admin/usuarios', \Src\App\Middlewares\AdminMiddleware::class);
$app->router->get('/', 'UsersController:index', 'admin.users.index');
$app->router->post('/', 'UsersController:store', 'admin.users.store');
$app->router->get('/{user_id}', 'UsersController:edit', 'admin.users.edit');
$app->router->put('/{user_id}', 'UsersController:update', 'admin.users.update');
$app->router->delete('/{user_id}', 'UsersController:delete', 'admin.users.delete');
$app->router->get('/criar', 'UsersController:create', 'admin.users.create');
$app->router->get('/list', 'UsersController:list', 'admin.users.list');

$app->router->namespace('Src\App\Controllers\Web');

$app->router->group('contato');
$app->router->get('/', 'ContactController:index', 'contact.index');
$app->router->post('/', 'ContactController:index', 'contact.index');

$app->router->namespace('Src\App\Controllers\User');

$app->router->group('u', \Src\App\Middlewares\UserMiddleware::class);
$app->router->get('/', 'UserController:index', 'user.index');

$app->router->group('u/editar', \Src\App\Middlewares\UserMiddleware::class);
$app->router->get('/', 'EditController:index', 'user.edit.index');
$app->router->put('/', 'EditController:update', 'user.edit.update');

$app->run();