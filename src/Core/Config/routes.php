<?php 

$router->namespace('Src\Core\App');

$router->group('erro');

$router->get('/{errcode}', 'Error:error');

$router->group('ml');

$router->post('/add', 'MediaLibrary:add', 'mediaLibrary.add');
$router->get('/load', 'MediaLibrary:load', 'mediaLibrary.load');
$router->delete('/delete', 'MediaLibrary:delete', 'mediaLibrary.delete');

$router->namespace('Src\Core\App\Admin');

$router->group('admin');

$router->get('/', 'CMain:main', 'CCAAMain.main');

$router->group('admin/config');

$router->put('/system', 'CConfig:system', 'CCAAConfig.system');
$router->put('/database', 'CConfig:database', 'CCAAConfig.database');
$router->put('/smtp', 'CConfig:smtp', 'CCAAConfig.smtp');
$router->put('/userytpes', 'CConfig:usertypes', 'CCAAConfig.usertypes');
$router->put('/save', 'CConfig:saveSingle', 'CCAAConfig.saveSingle');

$router->group('admin/menus');

$router->get('/', 'CMenus:main', 'CCAAMenus.main');
$router->post('/', 'CMenus:create', 'CCAAMenus.create');
$router->get('/{menu_id}', 'CMenus:get', 'CCAAMenus.get');
$router->put('/{menu_id}', 'CMenus:update', 'CCAAMenus.update');
$router->delete('/{menu_id}', 'CMenus:delete', 'CCAAMenus.delete');
$router->get('/criar', 'CMenus:get', 'CCAAMenus.creation');
$router->get('/list', 'CMenus:list', 'CCAAMenus.list');

$router->group('admin/usuarios');

$router->get('/', 'CUsers:main', 'CCAAUsers.main');
$router->post('/', 'CUsers:create', 'CCAAUsers.create');
$router->get('/{user_id}', 'CUsers:get', 'CCAAUsers.get');
$router->put('/{user_id}', 'CUsers:update', 'CCAAUsers.update');
$router->delete('/{user_id}', 'CUsers:delete', 'CCAAUsers.delete');
$router->get('/criar', 'CUsers:get', 'CCAAUsers.creation');
$router->get('/list', 'CUsers:list', 'CCAAUsers.list');

$router->group('admin/emails');

$router->get('/', 'CEmails:main', 'CCAAEmails.main');
$router->post('/', 'CEmails:create', 'CCAAEmails.create');
$router->get('/{email_id}', 'CEmails:get', 'CCAAEmails.get');
$router->put('/{email_id}', 'CEmails:update', 'CCAAEmails.update');
$router->delete('/{email_id}', 'CEmails:delete', 'CCAAEmails.delete');
$router->get('/criar', 'CEmails:get', 'CCAAEmails.creation');
$router->get('/list', 'CEmails:list', 'CCAAEmails.list');