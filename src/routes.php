<?php

$router = new \CoffeeCode\Router\Router(ROOT);

require __DIR__ . '/Core/Config/routes.php';

$router->namespace('Src\Example\App\Web');

$router->group(null);

$router->get('/', 'CLogin:main');
$router->post('/', 'CLogin:main', 'CEAWLogin.main');
$router->post('/expired-session', 'CLogin:expiredSession', 'CEAWLogin.expiredSession');
$router->post('/check-login', 'CLogin:check', 'CEAWLogin.check');

$router->group('contato');

$router->get('/', 'CContact:main');
$router->post('/', 'CContact:main', 'CEAWContact.main');

$router->group('entrar');

$router->get('/', 'CLogin:main');
$router->post('/', 'CLogin:main', 'CEAWLogin.main');

$router->group('logout');

$router->get('/', 'CMain:logout');

$router->group('redirect');

$router->get('/', 'CMain:redirect');

$router->group('redefinir-senha');

$router->get('/', 'CChangePassword:main', 'CEAWChangePassword.logout');
$router->post('/', 'CChangePassword:main', 'CEAWChangePassword.main');
$router->get('/{code}', 'CChangePassword:verify');
$router->post('/{code}', 'CChangePassword:verify', 'CEAWChangePassword.verify');

$router->group('criar-conta');

$router->get('/', 'CRegister:main');
$router->post('/', 'CRegister:main', 'CEAWRegister.main');

$router->namespace('Src\Example\App\User');

$router->group('u');

$router->get('/', 'CMain:main');

$router->group('u/editar-conta');

$router->get('/', 'CEditAccount:main');
$router->post('/save', 'CEditAccount:save', 'CEAUEditAccount.save');
$router->post('/validate-slug', 'CEditAccount:validateSlug', 'CEAUEditAccount.validateSlug');

$router->dispatch();

if($router->error()) {
    $router->redirect("/erro/{$router->error()}");
}