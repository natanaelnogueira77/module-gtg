<?php

namespace Src\Core\Exceptions;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Logger;

class ErrorHandler 
{
    protected $monolog;
    private $type = 0;
    protected $msg = '';
    protected $file = '';
    protected $line = 0;
    
    public function __construct(
        int $type, 
        string $msg, 
        ?string $file = null, 
        ?int $line = null
    ) 
    {
        $this->monolog = new Logger('web');
        //$this->monolog->pushHandler(new BrowserConsoleHandler(Logger::DEBUG));
        $this->monolog->pushHandler(new StreamHandler(__DIR__ . '/../../../errors.log', Logger::WARNING));
        $this->monolog->pushProcessor(function ($record) {
            $record['extra']['HTTP_HOST'] = $_SERVER['HTTP_HOST'];
            $record['extra']['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
            $record['extra']['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
            $record['extra']['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

            return $record;
        });

        $this->type = $type;
        $this->msg = $msg;
        $this->file = $file;
        $this->line = $line;
    }

    public static function control(
        int $type, 
        string $msg, 
        ?string $file = null, 
        ?int $line = null
    ) 
    {
        $instance = new self($type, $msg, $file, $line);
        switch($type) {
            case E_ERROR:
                $instance->errorControl();
                return 'E_ERROR';
            case E_WARNING: 
                $result = $instance->warningControl();
                return 'E_WARNING';
            case E_PARSE: 
                $instance->errorControl();
                return 'E_PARSE';
            case E_NOTICE:
                $result = $instance->noticeControl();
                return 'E_NOTICE';
            case E_CORE_ERROR: 
                $instance->errorControl();
                return 'E_CORE_ERROR';
            case E_CORE_WARNING:
                $result = $instance->warningControl();
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR:
                $instance->errorControl();
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING: 
                $result = $instance->warningControl();
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR:
                $instance->errorControl();
                return 'E_USER_ERROR';
            case E_USER_WARNING:
                $result = $instance->warningControl();
                return 'E_USER_WARNING';
            case E_USER_NOTICE: 
                $result = $instance->noticeControl();
                return 'E_USER_NOTICE';
            case E_STRICT:
                $instance->errorControl();
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR:
                $instance->errorControl();
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED:
                $instance->errorControl();
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED:
                $instance->errorControl();
                return 'E_USER_DEPRECATED';
        }
        return '';
    }

    public static function shutdown() 
    {
        $last_error = error_get_last();
        if($last_error['type'] === E_ERROR) {
            self::control(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
        }
    }

    protected function errorControl(): bool  
    {
        $content = "Arquivo: {$this->file}, Linha: {$this->line}, Mensagem: {$this->msg}";
        $this->monolog->error($content, ['logger' => true]);
        header('Location: ' . url('erro/500'));
        exit();
    }

    protected function warningControl(): bool 
    {
        $content = "Arquivo: {$this->file}, Linha: {$this->line}, Mensagem: {$this->msg}";
        $this->monolog->warning($content, ['logger' => true]);

        return true;
    }
    
    protected function noticeControl(): bool 
    {
        $content = "Arquivo: {$this->file}, Linha: {$this->line}, Mensagem: {$this->msg}";
        $this->monolog->notice($content, ['logger' => true]);

        return true;
    }
}