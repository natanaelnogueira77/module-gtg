<?php 

namespace Src\Config;

use GTG\MVC\Application;
use Src\Middlewares\{ 
    AdminMiddleware, 
    APIAdminMiddleware, 
    APIUserMiddleware, 
    GuestMiddleware, 
    UserMiddleware
};

final class AppRouter 
{
    private static ?AppRouter $instance = null;
    private Application $app;

    private function __construct(Application $app) 
    {
        $this->app = $app;
    }

    private function __clone()
    {}

    public static function getInstance(Application $app): AppRouter
    {
        if(!self::$instance) {
            self::$instance = new self($app);
        }

        return self::$instance;
    }

    public function setRoutes(): Application
    {
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
        return $this->app;
    }

    private function setHomeRoutes(): void 
    {
        $this->app->router->namespace('Src\Controllers');

        $this->app->router->group(null);
        $this->app->router->get('/', 'AuthController:index', 'home.index', GuestMiddleware::class);
    }

    private function setMediaLibraryRoutes(): void 
    {
        $this->app->router->namespace('Src\Controllers');

        $this->app->router->group('ml');
        $this->app->router->get('/', 'MediaLibraryController:loadFiles', 'mediaLibrary.loadFiles', APIUserMiddleware::class);
        $this->app->router->post('/', 'MediaLibraryController:addFile', 'mediaLibrary.addFile', APIUserMiddleware::class);
        $this->app->router->delete('/', 'MediaLibraryController:deleteFile', 'mediaLibrary.deleteFile', APIUserMiddleware::class);
    }

    private function setLanguageRoutes(): void 
    {
        $this->app->router->namespace('Src\Controllers');

        $this->app->router->group('language');
        $this->app->router->get('/', 'LanguageController:index', 'language.index');
    }

    private function setAuthenticationRoutes(): void
    {
        $this->app->router->namespace('Src\Controllers');
        
        $this->app->router->group('login');
        $this->app->router->get('/', 'AuthController:index', 'auth.index', GuestMiddleware::class);
        $this->app->router->post('/', 'AuthController:login', 'auth.login', GuestMiddleware::class);
        $this->app->router->post('/expired', 'AuthController:expired', 'auth.expired');
        $this->app->router->post('/check', 'AuthController:check', 'auth.check');

        $this->app->router->group('logout', UserMiddleware::class);
        $this->app->router->get('/', 'AuthController:logout', 'auth.logout');
    }

    private function setResetPasswordRoutes(): void 
    {
        $this->app->router->namespace('Src\Controllers');

        $this->app->router->group('reset-password', GuestMiddleware::class);
        $this->app->router->get('/', 'ResetPasswordController:index', 'resetPassword.index');
        $this->app->router->post('/', 'ResetPasswordController:email', 'resetPassword.email');
        $this->app->router->get('/{token}', 'ResetPasswordController:reset', 'resetPassword.reset');
        $this->app->router->post('/{token}', 'ResetPasswordController:verify', 'resetPassword.verify');
    }

    private function setAdminRoutes(): void 
    {
        $this->app->router->namespace('Src\Controllers');

        $this->app->router->group('admin');
        $this->app->router->get('/', 'AdminController:index', 'admin.index', AdminMiddleware::class);
        $this->app->router->put('/update-config', 'AdminController:updateConfig', 'admin.updateConfig', APIAdminMiddleware::class);
    }

    private function setUserRoutes(): void 
    {
        $this->app->router->namespace('Src\Controllers');

        $this->app->router->group('user');
        $this->app->router->get('/', 'UserController:index', 'user.index', UserMiddleware::class);
    }

    private function setNotificationsRoutes(): void 
    {
        $this->app->router->namespace('Src\Controllers');

        $this->app->router->group('notifications');
        $this->app->router->patch(
            '/mark-all-as-read', 
            'NotificationsController:markAllAsRead', 
            'notifications.markAllAsRead', 
            APIUserMiddleware::class
        );
    }

    private function setEditAccountRoutes(): void 
    {
        $this->app->router->namespace('Src\Controllers');

        $this->app->router->group('edit-account');
        $this->app->router->get('/', 'EditAccountController:index', 'editAccount.index', UserMiddleware::class);
        $this->app->router->put('/', 'EditAccountController:update', 'editAccount.update', APIUserMiddleware::class);
    }

    private function setUsersRoutes(): void 
    {
        $this->app->router->namespace('Src\Controllers');

        $this->app->router->group('users');
        $this->app->router->get('/', 'UsersController:index', 'users.index', AdminMiddleware::class);
        $this->app->router->post('/', 'UsersController:store', 'users.store', APIAdminMiddleware::class);
        $this->app->router->get('/{user_id}', 'UsersController:show', 'users.show', APIAdminMiddleware::class);
        $this->app->router->put('/{user_id}', 'UsersController:update', 'users.update', APIAdminMiddleware::class);
        $this->app->router->delete('/{user_id}', 'UsersController:delete', 'users.delete', APIAdminMiddleware::class);
        $this->app->router->get('/list', 'UsersController:list', 'users.list', APIAdminMiddleware::class);
    }
}