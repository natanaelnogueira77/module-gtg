<?php

namespace GTG\Pay\Exceptions;

use GTG\Pay\Exceptions\AppException;

class RequestException extends AppException 
{
    private $type;
    private $error;

    public function __construct($type, $error = null, $message = "", $code = 0, $previous = null) 
    {
        $this->type = $type;
        $this->error = $error;
        
        $message = $this->getErrorMessage();

        parent::__construct($message, $code, $previous);
    }

    private function getErrorMessage() 
    {
        if(!is_null($this->type)) {
            switch ($this->type) {
                case 404:
                    return "404: API Não encontrada";
                    break;
                case 500:
                    return "500: o servidor respondeu com um erro.";
                    break;
                case 502:
                    return "502: os servidores podem estar em manutenção. Eles estarão OK logo!";
                    break;
                case 503:
                    return "503: serviço indisponível. Eles estarão OK logo!";
                    break;
                default:
                    return "Erro não documentado: " . $this->type . " : " . $this->error;
                    break;
            }
        }
    }
}