<?php 

use GTG\MVC\Application;
use GTG\MVC\Database\Drivers\MySQLDriver;
use Config\AppRouter;
use Controllers\Controller;
use Exceptions\{ ApplicationException, RedirectException, ValidationException };

class Program 
{
    private static ?Application $app = null;
    private static ?array $environment = null;

    public static function init(): void 
    {
        self::setEnvironmentVariables();
        require_once(realpath(dirname(__FILE__) . '/Config/Utils.php'));

        try {
            self::setApp();
            self::setRouter();
            self::setAppData();
            self::setSession();
            self::setView();
            self::setDatabase();
            self::setSMTP();
            self::$app->apply();
        } catch(Exception $e) {
            echo $e->getMessage();
            die();
        }

        try {
            self::$app->run();
        } catch(Exception $e) {
            self::handleException($e);
        }
    }

    private static function handleException(Exception $e): void 
    {
        if($e instanceof ValidationException) {
            echo json_encode([
                'message' => ['error', $e->getMessage()],
                'errors' => $e->getErrors()
            ]);
        } elseif($e instanceof RedirectException) {
            self::$app->session->setFlash('error', $e->getMessage());
            header('Location: ' . $e->getRedirectURL());
            exit();
        } elseif($e instanceof ApplicationException) {
            echo json_encode(['message' => ['error', $e->getMessage()]]);
        } else {
            echo json_encode(['message' => ['error', $e->getMessage()]]);
        }
        
        http_response_code(intval($e->getCode()));
    }

    private static function setEnvironmentVariables(): void 
    {
        self::$environment = parse_ini_file(realpath(dirname(__FILE__, 2) . '/env.ini'));
    }

    private static function setApp(): void 
    {
        self::$app = new Application(dirname(__DIR__), self::getProjectUrl());
    }

    public static function getProjectUrl(): string 
    {
        return "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}" . str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
    }

    private static function setRouter(): void 
    {
        self::$app->setRouter(fn($router) => AppRouter::getInstance($router)->addRoutes());
    }

    private static function setAppData(): void 
    {
        self::$app->setAppData([
            'app_version' => self::$environment['app_version'],
            'app_name' => self::$environment['app_name'],
            'error_mail' => self::$environment['app_error_mail']
        ]);
    }

    private static function setSession(): void 
    {
        self::$app->session->setAuthKey(self::$environment['session_auth']);
        self::$app->session->setFlashKey(self::$environment['session_flash']);
        self::$app->session->setLanguageKey(self::$environment['session_lang']);
    }

    private static function setView(): void 
    {
        self::$app->setView('views', 'error');
    }

    private static function setDatabase(): void 
    {
        self::$app->database->setDriver(new MySQLDriver(
            self::$environment['db_name'],
            self::$environment['db_host'],
            self::$environment['db_port'],
            self::$environment['db_username'],
            self::$environment['db_password'],
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_CASE => PDO::CASE_NATURAL
            ]
        ));
    }

    private static function setSMTP(): void 
    {
        self::$app->SMTP->setConnection(
            self::$environment['smtp_host'],
            self::$environment['smtp_port'],
            self::$environment['smtp_username'],
            self::$environment['smtp_password'],
            self::$environment['smtp_name'],
            self::$environment['smtp_email']
        );
    }

    public static function getEnvironmentVariables(): ?array 
    {
        return self::$environment;
    }

    public static function applyMigrations(?int $numberOfMigrations = null): void 
    {
        self::setEnvironmentVariables();
        require_once(realpath(dirname(__FILE__) . '/Config/Utils.php'));
        self::setApp();
        self::setDatabase();
        self::$app->database->applyMigrations('src/Database/Migrations', 'Database\Migrations', $numberOfMigrations);
    }

    public static function reverseMigrations(?int $numberOfMigrations = null): void 
    {
        self::setEnvironmentVariables();
        require_once(realpath(dirname(__FILE__) . '/Config/Utils.php'));
        self::setApp();
        self::setDatabase();
        self::$app->database->reverseMigrations('src/Database/Migrations', 'Database\Migrations', $numberOfMigrations);
    }

    public static function applySeeders(): void 
    {
        self::setEnvironmentVariables();
        require_once(realpath(dirname(__FILE__) . '/Config/Utils.php'));
        self::setApp();
        self::setDatabase();
        self::$app->database->applySeeders('src/Database/Seeders', 'Database\Seeders');
    }

    public static function executeController(Controller $controller, string $method): void 
    {
        self::setEnvironmentVariables();
        require_once(realpath(dirname(__FILE__) . '/Config/Utils.php'));

        try {
            self::setApp();
            self::setRouter();
            self::setAppData();
            self::setView();
            self::setDatabase();
            self::setSMTP();
            self::$app->apply();
        } catch(Exception $e) {
            echo $e->getMessage();
            die();
        }

        try {
            self::setRouter();
            $newController = new $controller();
            $newController->$method();
        } catch(Exception $e) {
            self::handleException($e);
        }
    }
}