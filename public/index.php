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

// App Routes
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

$app->router->namespace('Src\App\Controllers');

$app->router->group('language');
$app->router->get('/{lang}', 'LanguageController:index', 'language.index');

$app->router->namespace('Src\App\Controllers\Admin');

$app->router->group('admin', \Src\App\Middlewares\AdminMiddleware::class);
$app->router->get('/', 'AdminController:index', 'admin.index');

$app->router->group('admin/usuarios', \Src\App\Middlewares\AdminMiddleware::class);
$app->router->get('/', 'UsersController:index', 'admin.users.index');
$app->router->get('/{user_id}', 'UsersController:edit', 'admin.users.edit');
$app->router->get('/criar', 'UsersController:create', 'admin.users.create');

$app->router->namespace('Src\App\Controllers\Web');

$app->router->group('contato');
$app->router->get('/', 'ContactController:index', 'contact.index');
$app->router->post('/', 'ContactController:index', 'contact.index');

$app->router->namespace('Src\App\Controllers\User');

$app->router->group('u', \Src\App\Middlewares\UserMiddleware::class);
$app->router->get('/', 'UserController:index', 'user.index');

$app->router->group('u/editar', \Src\App\Middlewares\UserMiddleware::class);
$app->router->get('/', 'EditController:index', 'user.edit.index');

// API Routes
$app->router->namespace('Src\API\Controllers');

$app->router->group('api/ml');
$app->router->post('/add', 'MediaLibraryController:add', 'api.mediaLibrary.add');
$app->router->get('/load', 'MediaLibraryController:load', 'api.mediaLibrary.load');
$app->router->delete('/delete', 'MediaLibraryController:delete', 'api.mediaLibrary.delete');

$app->router->namespace('Src\API\Controllers\Auth');

$app->router->group('api/login');
$app->router->post('/expired', 'AuthController:expired', 'api.auth.expired');
$app->router->post('/check', 'AuthController:check', 'api.auth.check');

$app->router->namespace('Src\API\Controllers\Admin');

$app->router->group('api/admin', \Src\API\Middlewares\AdminMiddleware::class);
$app->router->put('/system', 'AdminController:system', 'api.admin.system');

$app->router->group('api/admin/usuarios', \Src\API\Middlewares\AdminMiddleware::class);
$app->router->post('/', 'UsersController:store', 'api.admin.users.store');
$app->router->put('/{user_id}', 'UsersController:update', 'api.admin.users.update');
$app->router->delete('/{user_id}', 'UsersController:delete', 'api.admin.users.delete');
$app->router->get('/list', 'UsersController:list', 'api.admin.users.list');

$app->router->namespace('Src\API\Controllers\User');

$app->router->group('api/u/editar', \Src\API\Middlewares\UserMiddleware::class);
$app->router->put('/', 'EditController:update', 'api.user.edit.update');

$app->router->group('api/u/notificacoes', \Src\API\Middlewares\UserMiddleware::class);
$app->router->patch('/mark-all-as-read', 'NotificationsController:markAllAsRead', 'api.user.notifications.markAllAsRead');

$app->run();