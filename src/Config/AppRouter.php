<?php 

namespace Config;

use GTG\MVC\Router;
use Middlewares\{ 
    AdminMiddleware, 
    APIAdminMiddleware, 
    APIUserMiddleware, 
    GuestMiddleware, 
    UserMiddleware
};

final class AppRouter 
{
    private static ?AppRouter $instance = null;
    private Router $router;

    private function __construct(Router $router) 
    {
        $this->router = $router;
    }

    private function __clone()
    {}

    public static function getInstance(Router $router): AppRouter
    {
        if(!self::$instance) {
            self::$instance = new self($router);
        }

        return self::$instance;
    }

    public function addRoutes(): void
    {
        $this->router->namespace('Controllers');
        $this->setHomeRoutes();
        $this->setMediaLibraryRoutes();
        $this->setLanguageRoutes();
        $this->setAuthenticationRoutes();
        $this->setResetPasswordRoutes();
        $this->setAdminRoutes();
        $this->setUserRoutes();
        $this->setNotificationsRoutes();
        $this->setEditAccountRoutes();
        $this->setUsersRoutes();
    }

    private function setHomeRoutes(): void 
    {
        $this->router->group(null);
        $this->router->get('/', 'AuthController:index', 'home.index', GuestMiddleware::class);
    }

    private function setMediaLibraryRoutes(): void 
    {
        $this->router->group('ml');
        $this->router->get('/', 'MediaLibraryController:loadFiles', 'mediaLibrary.loadFiles', APIUserMiddleware::class);
        $this->router->post('/', 'MediaLibraryController:addFile', 'mediaLibrary.addFile', APIUserMiddleware::class);
        $this->router->delete('/', 'MediaLibraryController:deleteFile', 'mediaLibrary.deleteFile', APIUserMiddleware::class);
    }

    private function setLanguageRoutes(): void 
    {
        $this->router->group('language');
        $this->router->get('/', 'LanguageController:index', 'language.index');
    }

    private function setAuthenticationRoutes(): void
    {
        $this->router->group('login');
        $this->router->get('/', 'AuthController:index', 'auth.index', GuestMiddleware::class);
        $this->router->post('/', 'AuthController:login', 'auth.login', GuestMiddleware::class);
        $this->router->post('/expired', 'AuthController:expired', 'auth.expired');
        $this->router->post('/check', 'AuthController:check', 'auth.check');

        $this->router->group('logout', UserMiddleware::class);
        $this->router->get('/', 'AuthController:logout', 'auth.logout');
    }

    private function setResetPasswordRoutes(): void 
    {
        $this->router->group('reset-password', GuestMiddleware::class);
        $this->router->get('/', 'ResetPasswordController:index', 'resetPassword.index');
        $this->router->post('/', 'ResetPasswordController:email', 'resetPassword.email');
        $this->router->get('/{token}', 'ResetPasswordController:reset', 'resetPassword.reset');
        $this->router->post('/{token}', 'ResetPasswordController:verify', 'resetPassword.verify');
    }

    private function setAdminRoutes(): void 
    {
        $this->router->group('admin');
        $this->router->get('/', 'AdminController:index', 'admin.index', AdminMiddleware::class);
        $this->router->put('/update', 'AdminController:update', 'admin.update', APIAdminMiddleware::class);
        $this->router->delete('/reset', 'AdminController:reset', 'admin.reset', APIAdminMiddleware::class);
        $this->router->put('/update-config', 'AdminController:updateConfig', 'admin.updateConfig', APIAdminMiddleware::class);
    }

    private function setUserRoutes(): void 
    {
        $this->router->group('user');
        $this->router->get('/', 'UserController:index', 'user.index', UserMiddleware::class);
    }

    private function setNotificationsRoutes(): void 
    {
        $this->router->group('notifications');
        $this->router->patch('/mark-all-as-read', 'NotificationsController:markAllAsRead', 'notifications.markAllAsRead', APIUserMiddleware::class);
        $this->router->get('/get-all-unread', 'NotificationsController:getAllUnread', 'notifications.getAllUnread', APIUserMiddleware::class);
    }

    private function setEditAccountRoutes(): void 
    {
        $this->router->group('edit-account');
        $this->router->get('/', 'EditAccountController:index', 'editAccount.index', UserMiddleware::class);
        $this->router->put('/', 'EditAccountController:update', 'editAccount.update', APIUserMiddleware::class);
    }

    private function setUsersRoutes(): void 
    {
        $this->router->group('users');
        $this->router->get('/', 'UsersController:index', 'users.index', AdminMiddleware::class);
        $this->router->post('/', 'UsersController:store', 'users.store', APIAdminMiddleware::class);
        $this->router->get('/{user_id}', 'UsersController:show', 'users.show', APIAdminMiddleware::class);
        $this->router->put('/{user_id}', 'UsersController:update', 'users.update', APIAdminMiddleware::class);
        $this->router->delete('/{user_id}', 'UsersController:delete', 'users.delete', APIAdminMiddleware::class);
        $this->router->get('/list', 'UsersController:list', 'users.list', APIAdminMiddleware::class);
    }
}