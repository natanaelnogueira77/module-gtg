<?php

namespace Src\Core\System;

use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;
use Src\Core\System\Control;

class DatabaseConfig extends Control 
{
    protected static $columns = [
        'driver',
        'dbname',
        'host',
        'port',
        'username',
        'passwd'
    ];

    public function validate(): void 
    {
        $errors = [];

        if(!$this->driver) {
            $errors['driver'] = 'O Driver é um campo obrigatório!';
        }

        if(!$this->dbname) {
            $errors['dbname'] = 'Nome do Esquema é um campo obrigatório!';
        }

        if(!$this->host) {
            $errors['host'] = 'Host é um campo obrigatório!';
        }

        if(!$this->port) {
            $errors['port'] = 'Porta é um campo obrigatório!';
        }

        if(!$this->username) {
            $errors['username'] = 'Nome de Usuário é um campo obrigatório!';
        }

        if(!$this->passwd) {
            $errors['passwd'] = 'Por favor, informe a senha!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
    
    public function checkData() 
    {
        $this->validate();
        $path = realpath(dirname(__FILE__) . '/../../env.ini');
        foreach(self::$columns as $col) {
            $array[$col] = $this->$col;
        }
        $iniFile = writeIniFile($array, $path);
        if($iniFile) {
            return $iniFile;
        }

        throw new AppException('Não foi possível reescrever o arquivo ini!');
    }
}