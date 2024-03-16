<?php

namespace GTG\MVC;

use GTG\MVC\DTOs\ErrorDTO;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

final class ErrorHandler 
{
    protected Logger $monolog;
    private array $callbacks = [];

    public function __construct(string $projectPath) 
    {
        $this->monolog = new Logger('web');
        $this->setMonologOptions($projectPath);
        $this->errorCallback = null;
    }

    private function setMonologOptions($projectPath): void
    {
        $this->monolog->pushHandler(
            new StreamHandler($projectPath . '/errors.log', Logger::ERROR)
        );
        $this->monolog->pushProcessor(function ($record) {
            $record['extra']['HTTP_HOST'] = $_SERVER['HTTP_HOST'];
            $record['extra']['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
            $record['extra']['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
            $record['extra']['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

            return $record;
        });
    }

    public function setErrorCallback(callable $function): self
    {
        $this->callbacks['error'] = $function;
        return $this;
    }

    public function control(
        int $type, 
        string $message, 
        ?string $file = null, 
        ?int $line = null
    ): string 
    {
        $errorDTO = new ErrorDTO($type, $message, $file, $line);
        switch ($type) {
            case in_array($type, [E_NOTICE, E_USER_NOTICE]):
                $this->logNotice($errorDTO);
                break;
            case in_array($type, [E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING]):
                $this->logWarning($errorDTO);
                break;
            default: 
                $this->logError($errorDTO);
                $this->executeErrorCallback($errorDTO);
                break;
        }

        return $type;
    }

    private function logError(ErrorDTO $errorDTO): void  
    {
        $this->monolog->error($this->getErrorContent($errorDTO), ['logger' => true]);
        return;
    }

    private function logWarning(ErrorDTO $errorDTO): void 
    {
        $this->monolog->warning($this->getErrorContent($errorDTO), ['logger' => true]);
        return;
    }

    private function logNotice(ErrorDTO $errorDTO): void 
    {
        $this->monolog->notice($this->getErrorContent($errorDTO), ['logger' => true]);
        return;
    }

    private function getErrorContent(ErrorDTO $errorDTO): string 
    {
        return "File: {$errorDTO->getFile()} | Line: {$errorDTO->getLine()} | Message: {$errorDTO->getMessage()}";
    }

    private function executeErrorCallback(ErrorDTO $errorDTO): void
    {
        if($this->callbacks['error']) {
            $this->callbacks['error']($errorDTO);
        }
        return;
    }

    public function shutdown(): void 
    {
        $lastError = error_get_last();
        if($lastError['type'] === E_ERROR) {
            $this->control(
                500, 
                $lastError['message'], 
                $lastError['file'], 
                $lastError['line']
            );
        }
    }
}