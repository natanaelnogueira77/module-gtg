<?php

namespace Src\Core\System;

use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;
use Src\Core\System\Control;

class EmailConfig extends Control 
{
    protected static $columns = [
        'host',
        'port',
        'username',
        'password',
        'name',
        'email'
    ];

    public function validate(): void 
    {
        $errors = [];

        if(!$this->host) {
            $errors['host'] = 'Host é um campo obrigatório!';
        }

        if(!$this->port) {
            $errors['port'] = 'Porta é um campo obrigatório!';
        }

        if(!$this->username) {
            $errors['username'] = 'Nome de Usuário é um campo obrigatório!';
        }

        if(!$this->password) {
            $errors['password'] = 'Por favor, informe a senha!';
        }

        if(!$this->name) {
            $errors['name'] = 'Nome do Remetente é um campo obrigatório!';
        }

        if(!$this->email) {
            $errors['email'] = 'E-mail do Remetente é um campo obrigatório!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
    
    public function checkData() 
    {
        $this->validate();
        $path = realpath(dirname(__FILE__) . '/../../smtp.ini');
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