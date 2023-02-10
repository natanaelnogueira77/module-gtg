<?php

$router = new \CoffeeCode\Router\Router(ROOT);

$router->namespace('Src\App\Controllers\Auth');

$router->group(null);
$router->get('/', 'CLogin:index', 'home.index', \Src\App\Middlewares\MGuest::class);
$router->post('/', 'CLogin:index', 'home.index', \Src\App\Middlewares\MGuest::class);

$router->group('entrar', \Src\App\Middlewares\MGuest::class);
$router->get('/', 'CLogin:index', 'login.index');
$router->post('/', 'CLogin:index', 'login.index');
$router->post('/expired', 'CLogin:expired', 'login.expired');
$router->post('/check', 'CLogin:check', 'login.check');

$router->group('redefinir-senha', \Src\App\Middlewares\MGuest::class);
$router->get('/', 'CResetPassword:index', 'reset-password.index');
$router->post('/', 'CResetPassword:index', 'reset-password.index');
$router->get('/{code}', 'CResetPassword:verify', 'reset-password.verify');
$router->post('/{code}', 'CResetPassword:verify', 'reset-password.verify');

$router->group('criar-conta', \Src\App\Middlewares\MGuest::class);
$router->get('/', 'CRegister:index', 'register.index');
$router->post('/', 'CRegister:index', 'register.index');

$router->group('logout', \Src\App\Middlewares\MUser::class);
$router->get('/', 'CLogin:logout', 'login.logout');

$router->namespace('Src\App\Controllers');

$router->group('erro');
$router->get('/{code}', 'CError:index', 'error.index');

$router->group('ml');
$router->post('/add', 'CMediaLibrary:add', 'mediaLibrary.add');
$router->get('/load', 'CMediaLibrary:load', 'mediaLibrary.load');
$router->delete('/delete', 'CMediaLibrary:delete', 'mediaLibrary.delete');

$router->group('language');
$router->get('/{lang}', 'CLanguage:index', 'language.index');

$router->namespace('Src\App\Controllers\Admin');

$router->group('admin', \Src\App\Middlewares\MAdmin::class);
$router->get('/', 'CAdmin:index', 'admin.index');
$router->put('/system', 'CAdmin:system', 'admin.system');

$router->group('admin/usuarios', \Src\App\Middlewares\MAdmin::class);
$router->get('/', 'CUsers:index', 'admin.users.index');
$router->post('/', 'CUsers:store', 'admin.users.store');
$router->get('/{user_id}', 'CUsers:edit', 'admin.users.edit');
$router->put('/{user_id}', 'CUsers:update', 'admin.users.update');
$router->delete('/{user_id}', 'CUsers:delete', 'admin.users.delete');
$router->get('/criar', 'CUsers:create', 'admin.users.create');
$router->get('/list', 'CUsers:list', 'admin.users.list');

$router->namespace('Src\App\Controllers\Web');

$router->group('contato');
$router->get('/', 'CContact:index', 'contact.index');
$router->post('/', 'CContact:index', 'contact.index');

$router->namespace('Src\App\Controllers\User');

$router->group('u', \Src\App\Middlewares\MUser::class);
$router->get('/', 'CUser:index', 'user.index');

$router->group('u/editar', \Src\App\Middlewares\MUser::class);
$router->get('/', 'CEdit:index', 'user.edit.index');
$router->put('/', 'CEdit:update', 'user.edit.update');

$router->dispatch();

if($router->error()) {
    $router->redirect("/erro/{$router->error()}");
}