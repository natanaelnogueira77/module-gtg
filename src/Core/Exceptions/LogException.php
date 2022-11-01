<?php

namespace Src\Core\Exceptions;

use DateTime;
use Exception;

class LogException extends Exception 
{
    // Email de quem deve receber o aviso de erro
    protected $log_report = 'log_exception.txt';
    protected $log_exception = 'log_exception.log';
    protected $log_exception_date = 'log_exception_data.txt';
    var $mailAdmin = MAIN['error_mail'];

    public function __construct($errorContent, $message = null, $code = 0) 
    {
        parent::__construct($message, $code);
        $hoje = date('Y-m-d H:i:s');
        $msg_error = 
            "\n ====== {$hoje} ======" .
            "\n Erro no arquivo : " . $this->getFile().
            "\n Linha:      " . $this->getLine() .
            "\n Mensagem:   " . $this->getMessage() .
            "\n Codigo:     " . $this->getCode() .
            "\n Trace(str): " . $this->getTraceAsString() . 
            "\n Mensagem de Erro: " . $errorContent . "\n";
        
        $this->writeLog($msg_error);
        
        switch($code) {
            case E_USER_ERROR: 
            case E_USER_WARNING: 
            case E_WARNING:
                return error_log($msg_error, 1, $this->log_exception); 
                break;
            case E_USER_NOTICE:
            case E_NOTICE:
                error_log($msg_error, 3, 'log_de_erros.dat'); 
                break;
            default:
                return false; 
                break;
        }
    }

    private function isReportToday(): bool
    {
        $lastReport = file_get_contents($this->log_exception_date);
        $today = date('Y-m-d');

        $date1 = (new DateTime($today))->format('Y-m-d');
        $date2 = (new DateTime($lastReport))->format('Y-m-d');

        if($date1 == $date2) {
            return true;
        } else {
            return false;
        }
    }

    private function writeLog($log): void 
    {
        if($this->isReportToday()) {
            $file = fopen($this->log_report, 'a');
            fwrite($file, $log);
            fclose($file);
        } else {
            $file = fopen($this->log_report, 'a');
            fwrite($file, $log);
            fclose($file);

            $this->sendReport();
        }
    }

    private function sendReport(): bool 
    {
        $report = file_get_contents($this->log_report);
        $file = fopen($this->log_report, 'w');
        fwrite($file, '');
        fclose($file);

        return error_log($report, 1, $this->getAdminMail(), 'Relatório de Erros - ' . ROOT);
    }

    private function getAdminMail(): string 
    {
        return $this->mailAdmin;
    }
}