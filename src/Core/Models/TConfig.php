<?php

namespace Src\Core\Models;

use Exception;
use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;

trait TConfig 
{
    public function decodeJSON() 
    {
        $metaColumn = self::$elements['meta'];
        $valueColumn = self::$elements['value'];

        if(in_array($this->$metaColumn, self::$jsonValues)) {
            if(gettype($this->$valueColumn) === 'string') {
                $this->$valueColumn = json_decode($this->$valueColumn, 
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        return $this;
    }

    public function encodeJSON() 
    {
        $metaColumn = self::$elements['meta'];
        $valueColumn = self::$elements['value'];

        if(in_array($this->$metaColumn, self::$jsonValues)) {
            if(gettype($this->$valueColumn) === 'array') {
                $this->$valueColumn = json_encode($this->$valueColumn, 
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        return $this;
    }

    public static function setMetas($metas = []): void
    {
        self::$metas = $metas;
    }

    public static function addMetas($metas = []): void
    {
        self::$metas = array_merge(self::$metas, $metas);
    }

    public static function setJSONValues($jsonValues = []): void
    {
        self::$jsonValues = $jsonValues;
    }

    public static function addJSONValues($jsonValues = []): void
    {
        self::$jsonValues = array_merge(self::$jsonValues, $jsonValues);
    }

    public static function getMetaValues(array $filters = [], string $columns = '*'): array
    {
        $result = self::get($filters, $columns);
        $array = [];

        if($result) {
            foreach($result as $obj) {
                $obj->decodeJSON();
                $array[$obj->{self::$elements['meta']}] = $obj->{self::$elements['value']};
            }
        }

        return $array;
    }

    public static function getAllByMetaName(string $meta = ''): ?array 
    {
        $results = self::get([self::$elements['meta'] => $meta]);

        if($results) {
            foreach($results as $result) {
                $result->decodeJSON();
            }
        }

        return $results;
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

    public static function getMetaByName(string $meta = '')
    {
        if($meta) {
            $result = self::getOne([self::$elements['meta'] => $meta]);
            if($result) {
                return $result->value;
            }
        }

        return null;
    }

    public static function getMetasByName(array $metas = []): array
    {
        $array = [];
        if($metas) {
            $in = '';
            foreach($metas as $meta) {
                if(in_array($meta, self::$metas)) {
                    $in .= "'{$meta}',";
                }
            }

            $in[strlen($in) - 1] = ' ';
            $results = self::get([
                'raw' => self::$elements['meta'] . " IN ({$in})"
            ]);

            if($results) {
                foreach($results as $result) {
                    $result->decodeJSON();
                    $array[$result->{self::$elements['meta']}] = $result->{self::$elements['value']};
                }
            }
        }

        return $array;
    }

    private static function metaHandler(string $meta, $value): void 
    {
        if($meta) {
            $methodName = self::$handlers[$meta];
            if($methodName && method_exists(new self, $methodName)) {
                self::$methodName($value);
            }
        }
    }

    public static function saveMetas(array $values = []): void 
    {
        $done = [];
        $errors = [];

        if($values) {
            // Setting up the search for the database rows with the selected metas
            foreach($values as $meta => $value) {
                if(in_array($meta, self::$metas)) {
                    try {
                        self::metaHandler($meta, $value);
                    } catch(Exception $e) {
                        if((new \ReflectionClass($e))->getShortName() == 'ValidationException') {
                            $errors[$meta] = $e->getErrors();
                        }
                    }
                    $in .= "'{$meta}',";
                }
            }

            if(count($errors) > 0) {
                throw new ValidationException($errors, 'Erros de validação! Verifique os dados!');
            }

            $in[strlen($in) - 1] = ' ';

            $results1 = self::get([
                'raw' => self::$elements['meta'] . " IN ({$in})"
            ]);
            // Update Objects
            if($results1) {
                foreach($results1 as $result) {
                    if(in_array($result->{self::$elements['meta']}, self::$metas)) {
                        $result->{self::$elements['value']} = $values[$result->{self::$elements['meta']}];
                        $result->encodeJSON();
                        $done[] = $result->{self::$elements['meta']};
                    }
                }
            }
            // Insert Objects
            foreach($values as $meta => $value) {
                if(in_array($meta, self::$metas) 
                    && !in_array($meta, $done)) {
                    $object = new self();
                    $object->setValues([
                        self::$elements['meta'] => $meta
                    ]);
                    $object->{self::$elements['value']} = $value;
                    $object->encodeJSON();

                    $results2[] = $object;
                }
            }

            if($results1) self::updateObjects($results1); // Multiple Update
            if($results2) self::insertObjects($results2); // Multiple Insert
        }
    }
}