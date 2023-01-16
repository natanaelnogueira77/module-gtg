<?php

namespace Src\App\Controllers;

use DateTime;
use DateInterval;
use Exception;
use League\Plates\Engine;
use ReflectionClass;
use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;

class Controller 
{
    protected $view;
    protected $message;
    protected $router;
    protected $error;

    public function __construct($router) 
    {
        $this->router = $router;
        $this->view = new Engine(__DIR__ . "/../../../resources/views", 'php');
        $this->view->addData(['router' => $router]);
    }

    protected function setMessage(string $message, string $type = 'success'): void
    {
        $this->message = message($message, $type);
        if($this->message['type'] == 'error') {
            throw new AppException($this->message['message']);
        }
    }

    protected function setTheme(string $themeName, array $data = []): void
    {
        $this->view = new Engine(__DIR__ . "/../../../resources/views", 'php');
        $this->view->addData($data);
    }

    protected function getMessage(): ?array 
    {
        return $this->message;
    }

    protected function getRoute(string $route, array $params = []): ?string 
    {
        return $this->router->route($route, $params);
    }

    protected function redirect(string $key, $params = []) 
    {
        header('Location: ' . $this->getRoute($key, $params));
        exit();
    }

    protected function throwException(string $msg): void 
    {
        throw new Exception($msg);
    }

    protected function loadView(string $vName = '', array $params = array()): void 
    {
        $this->view->addData($params);
        echo $this->view->render($vName);
    }

    protected function getView(string $vName = '', array $params = array())  
    {
        $view = $this->view;
        $view->addData($params);
        return $view->render($vName);
    }

    protected function echoCallback($callback): void 
    {
        if($this->error) {
            $callback['message'] = message($this->error->getMessage(), 'error');
            if((new ReflectionClass($this->error))->getShortName() == 'ValidationException') {
                $callback['errors'] = $this->error->getErrors();
            }
        }

        if($this->message) {
            $callback['message'] = $this->message;
        }

        echo json_encode($callback);
    }

    protected function getDateTime(?string $date = null): DateTime 
    {
        return new DateTime($date ? $date : '');
    }

    protected function getError(): ?Exception 
    {
        return $this->error;
    }
}