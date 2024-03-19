<?php 

namespace GTG\MVC;

use GTG\MVC\Database\Database;
use GTG\MVC\Exceptions\AppException;
use GTG\MVC\DTOs\ErrorDTO;
use GTG\MVC\{ ErrorHandler, Router, Session, SMTP, View };

final class Application 
{
    public static Application $instance;
    public ?array $appData = null;
    public Database $database;
    public ?Router $router = null;
    public Session $session;
    public SMTP $SMTP;
    public ?View $view = null;
    private string $projectPath;
    private ErrorHandler $errorHandler;

    public function __construct(string $projectPath) 
    {
        $this->projectPath = $projectPath;
        $this->errorHandler = new ErrorHandler($projectPath);
        $this->database = new Database($projectPath);
        $this->SMTP = new SMTP();
        $this->session = new Session();
        self::$instance = $this;
    }

    public static function getInstance(): Application 
    {
        return self::$instance;
    }

    public function setRouter(string $projectURL): self 
    {
        if(!isset($_SERVER['REQUEST_METHOD'])) {
            throw new AppException('No Request Method was settled for the Router!');
        }
        $this->router = new Router($projectURL);
        return $this;
    }

    public function setView(string $relativePath): self 
    {
        $this->view = new View($this->projectPath . '/' . $relativePath);
        return $this;
    }

    public function setAppData(array $data): self 
    {
        $this->appData = $data;
        return $this;
    }

    public function apply(): void 
    {
        $this->setErrorHandling();
        $this->setLocaleAndLanguage();
    }

    private function setErrorHandling(): void 
    {
        $this->setErrorConfigurationOptions();
        $this->setErrorCallback();
        $this->configureErrorHandler();
    }

    private function setErrorConfigurationOptions(): void 
    {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 1);
        ini_set('ignore_repeated_source', true);
        ini_set('log_errors', true);
        error_reporting(E_ALL);
    }

    private function setErrorCallback(): void 
    {
        if($this->view) {
            $app = $this;
            $this->errorHandler->setErrorCallback(function($error) use ($app) {
                $app->renderErrorPage($error);
            });
        }
    }

    private function renderErrorPage(ErrorDTO $error): void
    {
        echo $this->view && $this->view->getErrorPagePath() 
            ? $this->view->getContext()->render($this->view->getErrorPagePath(), [
                'appData' => $this->appData,
                'router' => $this->router,
                'session' => $this->session,
                'error' => $error
            ]) 
            : '';
    }

    private function configureErrorHandler(): void 
    {
        set_error_handler(array($this->errorHandler, 'control'), E_ALL);
        register_shutdown_function(array($this->errorHandler, 'shutdown'));
    }

    private function setLocaleAndLanguage(): void 
    {
        if($this->session->getLanguage()) {
            setlocale(LC_ALL, $this->session->getLanguage()[1]);
            putenv('LANGUAGE=' . $this->session->getLanguage()[1]);
        }

        date_default_timezone_set('America/Recife');
        bindtextdomain('messages', $this->projectPath . '/lang');
        bind_textdomain_codeset('messages', 'UTF-8');
        textdomain('messages');
    }

    public function run(): void 
    {
        $this->dispatchRouter();
        $this->handleRouterError();
    }

    private function dispatchRouter(): void
    {
        if(!$this->router) {
            throw new AppException('Error at run: No Router was settled!');
        }

        $this->router->dispatch();
    }

    private function handleRouterError(): void 
    {
        if($this->router->error()) {
            http_response_code($this->router->error());
            $this->renderErrorPage(new ErrorDTO(
                $this->router->error(), 
                'Router error!'
            ));
        }
    }
}