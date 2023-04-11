<?php

namespace Src\Exceptions;

use DateTime;
use Src\Exceptions\AppException;
use Throwable;

class LogException extends AppException 
{
    protected $exceptions_log = 'exceptions_log.log';
    protected $exceptions_log_date = 'exceptions_log_data.txt';
    var $mailAdmin = ERROR_MAIL;

    public function __construct(string $errorContent, string $message, int $code = 0, ?Throwable $previous = null) 
    {
        parent::__construct($message, $code, $previous);
        $errorMessage = 
            "\n ====== " . date('Y-m-d H:i:s') . " ======" .
            "\n Erro no arquivo : " . $this->getFile().
            "\n Linha:      " . $this->getLine() .
            "\n Mensagem:   " . $this->getMessage() .
            "\n Codigo:     " . $this->getCode() .
            "\n Trace(str): " . $this->getTraceAsString() . 
            "\n Mensagem de Erro: " . $errorContent . "\n";
        
        $this->writeLog($errorMessage);
        
        switch($code) {
            case E_USER_ERROR: 
            case E_USER_WARNING: 
            case E_WARNING:
                return error_log($errorMessage, 1, $this->exceptions_log); 
                break;
            case E_USER_NOTICE:
            case E_NOTICE:
                error_log($errorMessage, 3, 'errors_log.dat'); 
                break;
            default:
                return false; 
                break;
        }
    }

    private function isReportToday(): bool
    {
        return (new DateTime(date('Y-m-d')))->format('Y-m-d') == (new DateTime(file_get_contents($this->exceptions_log_date)))->format('Y-m-d');
    }

    private function writeLog(string $log): void 
    {
        if($this->isReportToday()) {
            $file = fopen($this->exceptions_log, 'a');
            fwrite($file, $log);
            fclose($file);
        } else {
            $file = fopen($this->exceptions_log, 'a');
            fwrite($file, $log);
            fclose($file);

            $this->sendReport();
        }
    }

    private function sendReport(): bool 
    {
        $report = file_get_contents($this->exceptions_log);
        $file = fopen($this->exceptions_log, 'w');
        fwrite($file, '');
        fclose($file);

        return error_log($report, 1, $this->getAdminMail(), 'Relatório de Erros - ' . ROOT);
    }

    private function getAdminMail(): string 
    {
        return $this->mailAdmin;
    }
}