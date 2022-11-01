<?php

namespace Src\Core\System;

use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;
use Src\Core\System\Control;

class Main extends Control 
{
    protected static $columns = [
        'error_mail',
        'root',
        'sitename',
        'sessname',
        'version'
    ];

    public function validate(): void 
    {
        $errors = [];

        if(!$this->error_mail) {
            $errors['error_mail'] = 'Este campo não pode ficar vazio!';
        }

        if(!$this->root) {
            $errors['root'] = 'Este campo não pode ficar vazio!';
        }

        if(!$this->sitename) {
            $errors['sitename'] = 'Este campo não pode ficar vazio!';
        }

        if(!$this->sessname) {
            $errors['sessname'] = 'Este campo não pode ficar vazio!';
        }

        if(!$this->version) {
            $errors['version'] = 'Este campo não pode ficar vazio!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
    
    public function checkData() 
    {
        $this->validate();
        $path = realpath(dirname(__FILE__) . '/../../main.ini');
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