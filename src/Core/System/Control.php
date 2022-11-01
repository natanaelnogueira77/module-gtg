<?php

namespace Src\Core\System;

class Control 
{
    protected $values = [];

    function __construct(array $arr, bool $sanitize = true) 
    {
        $this->loadFromArray($arr, $sanitize);
    }
    
    public function loadFromArray(array $arr, bool $sanitize = true) 
    {
        if($arr) {
            foreach($arr as $key => $value) {
                if(!is_null($value) && gettype($value) === 'string') {
                    $cleanValue = html_entity_decode($value);
                } else {
                    $cleanValue = $value;
                }
                
                $cleanKey = $key;
                if($sanitize && isset($cleanValue)) {
                    $cleanValue = strip_tags(trim($cleanValue));
                    $cleanValue = htmlentities($cleanValue, ENT_NOQUOTES);
                }
                $this->$key = $cleanValue;
            }
        }
    }

    public function __get($key) 
    {
        return $this->values[$key];
    }

    public function __set($key, $value) 
    {
        $this->values[$key] = $value;
    }

    public function getValues(): array 
    {
        return $this->values;
    }
}