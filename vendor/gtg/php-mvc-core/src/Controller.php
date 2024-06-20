<?php

namespace GTG\MVC;

use DateTime;
use GTG\MVC\Utils\{ Email, ExcelGenerator, PDFGenerator };
use GTG\MVC\Database\Database;

abstract class Controller 
{
    protected ?array $appData;
    protected Router $router;
    protected Session $session;
    private Database $database;
    private SMTP $SMTP;

    public function __construct() 
    {
        $this->appData = Application::getInstance()->appData;
        $this->router = Application::getInstance()->router;
        $this->session = Application::getInstance()->session;
        $this->database = Application::getInstance()->database;
        $this->SMTP = Application::getInstance()->SMTP;
    }

    protected function redirect(string $routeKey, array $params = []): void 
    {
        header("Location: " . $this->router->route($routeKey, $params));
        exit();
    }

    protected function createResponse(array $data, int $statusCode = 200): Response 
    {
        return new Response($data, $statusCode);
    }
}