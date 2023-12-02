<?php 

namespace GTG\MVC;

use GTG\MVC\Controller;
use GTG\MVC\DB\Database;
use GTG\MVC\Middleware;
use GTG\MVC\Request;
use GTG\MVC\Response;
use GTG\MVC\Router;
use GTG\MVC\Session;
use GTG\MVC\View;

class Application 
{
    public static string $ROOT_DIR;
    public static ?array $DB_INFO = null;
    public static ?array $SMTP_INFO = null;
    public static Application $app;
    public ?array $appData = null;
    public ?string $errorView = null;
    public Controller $controller;
    public ?Database $db = null;
    public Request $request;
    public Response $response;
    public ?Router $router = null;
    public ?Session $session = null;
    public ?View $view = null;

    public function __construct(string $rootPath) 
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
    }

    public function setRouter(string $appUrl): self 
    {
        $this->router = isset($_SERVER['REQUEST_METHOD']) ? new Router($appUrl) : null;
        return $this;
    }

    public function setSessionParams(string $authKey, string $flashKey, string $languageKey): self 
    {
        $this->session = new Session($authKey, $flashKey, $languageKey);
        return $this;
    }

    public function setDatabaseConnection(
        string $driver,
        string $dbname,
        string $host,
        string $port,
        string $username,
        string $password,
        ?array $options = null
    ): self 
    {
        self::$DB_INFO = [
            'driver' => $driver,
            'dbname' => $dbname,
            'host' => $host,
            'port' => $port,
            'username' => $username,
            'passwd' => $password,
            'options' => $options
        ];
        $this->db = new Database(self::$DB_INFO);
        return $this;
    }

    public function setMigrations(string $relativePath, string $namespace): self 
    {
        if($this->db) {
            $this->db->setMigrations($relativePath, $namespace);
        }
        return $this;
    }

    public function setSeeders(string $relativePath, string $namespace): self 
    {
        if($this->db) {
            $this->db->setSeeders($relativePath, $namespace);
        }
        return $this;
    }

    public function setSMTP(
        string $host,
        string $port,
        string $username,
        string $password,
        string $name,
        string $email
    ): self 
    {
        self::$SMTP_INFO = [
            'host' => $host,
            'port' => $port,
            'username' => $username,
            'password' => $password,
            'name' => $name,
            'email' => $email
        ];
        return $this;
    }

    public function setViews(string $viewPath, ?string $errorView = null): self 
    {
        $this->view = new View($viewPath);
        $this->errorView = $errorView;
        return $this;
    }

    public function setAppData(array $data): self 
    {
        $this->appData = $data;
        return $this;
    }

    public function apply(): void 
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->controller = new Controller();

        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 1);
        ini_set('ignore_repeated_source', true);
        ini_set('log_errors', true);
        error_reporting( E_ALL );

        set_error_handler(array('GTG\MVC\Exceptions\ErrorHandler', 'control'), E_ALL);
        register_shutdown_function(array('GTG\MVC\Exceptions\ErrorHandler', 'shutdown'));

        setlocale(LC_ALL, $this->session->getLanguage()[1]);
        putenv('LANGUAGE=' . $this->session->getLanguage()[1]);

        bindtextdomain('messages', self::$ROOT_DIR . '/lang');
        bind_textdomain_codeset('messages', 'UTF-8');
        textdomain('messages');

        date_default_timezone_set('America/Recife');

        return;
    }

    public function run(): void 
    {
        if($this->router) {
            $this->router->dispatch();
            if($this->router->error()) {
                $this->response->setStatusCode($this->router->error());
                if($this->errorView) {
                    echo $this->view->render($this->errorView, [
                        'appData' => $this->appData,
                        'router' => $this->router,
                        'session' => $this->session,
                        'code' => $this->router->error()
                    ]);
                }
            }
        }
        return;
    }

    public function getController(): Controller 
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void 
    {
        $this->controller = $controller;
    }
}