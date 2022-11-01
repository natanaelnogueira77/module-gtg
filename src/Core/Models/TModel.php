<?php

namespace Src\Core\Models;

trait TModel 
{
    public function decodeJSON() 
    {
        foreach(self::$columns as $col) {
            if(in_array($col, self::$jsonValues)) {
                if(gettype($this->$col) === 'string') {
                    $this->$col = json_decode($this->$col, 
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            }
        }

        return $this;
    }

    public function encodeJSON() 
    {
        foreach(self::$columns as $col) {
            if(in_array($col, self::$jsonValues)) {
                if(gettype($this->$col) === 'array') {
                    $this->$col = json_encode($this->$col, 
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            }
        }

        return $this;
    }

    public static function get(array $filters = [], string $columns = '*'): ?array 
    {
        $result = parent::get($filters, $columns);
        if($result) {
            foreach($result as $object) {
                $object->decodeJSON();
            }
        }

        return $result;
    }

    public static function getOne(array $filters = [], string $columns = '*') 
    {
        $result = parent::getOne($filters, $columns);
        if($result) {
            $result->decodeJSON();
        }

        return $result;
    }

    public static function getById(int $id, string $columns = '*') 
    {
        $result = parent::getById($id, $columns);
        if($result) $result->decodeJSON();

        return $result;
    }
}