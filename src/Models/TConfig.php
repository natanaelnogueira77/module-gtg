<?php

namespace Src\Models;

use Exception;
use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;

trait TConfig 
{
    public function decodeJSON(): static 
    {
        $metaColumn = self::$elements['meta'];
        $valueColumn = self::$elements['value'];

        if(in_array($this->$metaColumn, self::$jsonValues)) {
            if(gettype($this->$valueColumn) === 'string') {
                $this->$valueColumn = json_decode($this->$valueColumn, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        return $this;
    }

    public function encodeJSON(): static 
    {
        $metaColumn = self::$elements['meta'];
        $valueColumn = self::$elements['value'];

        if(in_array($this->$metaColumn, self::$jsonValues)) {
            if(gettype($this->$valueColumn) === 'array') {
                $this->$valueColumn = json_encode($this->$valueColumn, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        return $this;
    }

    public static function getMetaValues(array $filters = [], string $columns = '*'): array
    {
        $result = (new self())->get($filters, $columns)->fetch(true);
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
        $results = (new self())->get([self::$elements['meta'] => $meta])->fetch(true);

        if($results) {
            foreach($results as $result) {
                $result->decodeJSON();
            }
        }

        return $results;
    }

    public static function getMetaByName(string $meta = '')
    {
        if($meta) {
            $result = (new self())->get([self::$elements['meta'] => $meta])->fetch(false);
            if($result) {
                $result->decodeJSON();
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
            $results = (new self())->get([
                'raw' => self::$elements['meta'] . " IN ({$in})"
            ])->fetch(true);

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
                throw new ValidationException($errors, _('Erros de validação! Verifique os dados!'));
            }

            $in[strlen($in) - 1] = ' ';

            $results1 = (new self())->get([
                'raw' => self::$elements['meta'] . " IN ({$in})"
            ])->fetch(true);
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

            if($results1) self::updateMany($results1); // Multiple Update
            if($results2) self::insertMany($results2); // Multiple Insert
        }
    }
}