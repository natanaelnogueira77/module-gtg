<?php

namespace Src\Core\Models;

use Exception;
use GTG\DataLayer\Connect;
use GTG\DataLayer\DataLayer;
use PDO;
use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\LogException;

class Model extends DataLayer 
{
    protected static $tableName = '';
    protected static $primaryKey = '';
    protected static $columns = [];
    protected static $required = [];
    protected static $driver = DATA_LAYER['driver'];
    
    protected $values = [];

    public function __construct() 
    {
        parent::__construct(static::$tableName, static::$required, static::$primaryKey, false);
    }

    public function __get($key) 
    {
        parent::__get($key);
        return $this->values[$key];
    }

    public function __set($key, $value) 
    {
        parent::__set($key, $value);
        $this->values[$key] = $value;
    }

    public function getValues(): array 
    {
        foreach(static::$columns as $col) {
            $this->values[$col] = $this->data->$col;
        }
        return $this->values;
    }

    public function setValues(array $values = []): void 
    {
        foreach($values as $column => $value) {
            $this->$column = $value;
            $this->values[$column] = $value;
        }
    }

    public static function getTableName(): string 
    {
        return static::$tableName;
    }

    public static function getPropertyValues(array $objects = [], string $property = 'id'): array 
    {
        $values = [];

        if($objects) {
            foreach($objects as $object) {
                $values[] = $object->$property;
            }
        }

        return $values;
    }

    public static function getOne(array $filters = [], string $columns = '*') 
    {
        $class = get_called_class();
        $instance = new $class();

        $finders = static::getFilters($filters);

        $result = $instance->find($finders[0], $finders[1])->fetch(false);
        if($instance->fail()) {
            throw new LogException(
                $instance->fail()->getMessage(),
                'Ocorreu um erro inesperado enquando você executava essa ação! Informe ao Administrador.'
            );
        }

        if($result) {
            return $result;
        }

        return null;
    }

    public static function get(array $filters = [], string $columns = '*'): ?array 
    {
        $class = get_called_class();
        $instance = new $class();

        $finders = static::getFilters($filters);

        $objects = [];
        $objects = $instance->find($finders[0], $finders[1], $columns)->fetch(true);
        if($instance->fail()) {
            throw new LogException(
                $instance->fail()->getMessage(),
                'Ocorreu um erro inesperado enquando você executava essa ação! Informe ao Administrador.'
            );
        }

        if($objects) {
            return $objects;
        }

        return null;
    }

    public static function getById(int $id, string $columns = '*') 
    {
        $class = get_called_class();
        $instance = new $class();

        $result = $instance->findById($id, $columns);
        if($instance->fail()) {
            throw new LogException(
                $instance->fail()->getMessage(),
                'Ocorreu um erro inesperado enquando você executava essa ação! Informe ao Administrador.'
            );
        }

        return $result;
    }

    public static function getByIds(array $ids, string $columns = '*') 
    {
        $class = get_called_class();
        $instance = new $class();

        $in = implode(',', $ids);

        $result = $instance->find(static::$primaryKey . " IN ({$in})", null, $columns)->fetch(true);
        if($instance->fail()) {
            throw new LogException(
                $instance->fail()->getMessage(),
                'Ocorreu um erro inesperado enquando você executava essa ação! Informe ao Administrador.'
            );
        }

        return $result;
    }

    public static function getGroupedBy(array $objects = [], string $column = ''): ?array 
    {
        if(!$column) $column = static::$primaryKey;

        if($objects) {
            foreach($objects as $object) {
                $grouped[$object->$column] = $object;
            }

            return $grouped;
        }
        return null;
    }

    public static function getCount(array $filters = [], array $joins = []): ?int 
    {
        $count = 0;

        try {
            $class = get_called_class();
            $instance = new $class();
            $finders = static::getFilters($filters);
            $entities = static::getJoins($joins);

            $count = $instance->join($entities)->find($finders[0], $finders[1])->count();
        } catch(Exception $e) {
            throw new LogException(
                $e->getMessage(),
                'Ocorreu um erro inesperado enquando você executava essa ação! Informe ao Administrador.'
            );
        }

        return $count;
    }

    public static function getByRawSQL(string $sql) 
    {
        $class = get_called_class();
        $model = new $class();
        $model->statement($sql);
        $results = $model->fetch(true);

        if($model->fail()) {
            throw new LogException(
                $model->fail()->getMessage(),
                'Ocorreu um erro inesperado enquando você executava essa ação! Informe ao Administrador.'
            );
        }

        return $results;
    }

    public function save(): bool 
    {
        foreach(static::$columns as $col) {
            if(!is_null($this->$col)) {
                $this->$col = html_entity_decode($this->$col);
            } else {
                unset($this->$col);
            }
        }

        $result = parent::save();
        if($this->fail()) {
            throw new LogException(
                $this->fail()->getMessage(),
                'Ocorreu um erro inesperado enquando você executava essa ação! Informe ao Administrador.'
            );
        }

        $this->{static::$primaryKey} = $this->data->{static::$primaryKey};
        return $result;
    }

    public static function updateObjects(array $objects = []): void 
    {
        if(count($objects) > 0) {
            [$sql, $vars] = static::getUpsertSQL($objects);
            $connect = Connect::getInstance();
            $stmt = $connect->prepare($sql);
            $stmt->execute($vars);
        }
    }

    private static function getUpsertSQL(array $objects = []): array 
    {
        $sql = '';
        $vars = [];

        if(static::$driver == 'mysql') {
            $sql = 'INSERT INTO ' . static::$tableName . ' (' . static::$primaryKey . ','
                . implode(',', static::$columns) . ') VALUES ';

            foreach($objects as $object) {
                $sql .= '(' . static::getFormatedValue($object->{static::$primaryKey}) . ',';
                foreach(static::$columns as $col) {
                    $vars[] = $object->$col;
                    $sql .= '?,';
                }

                $sql[strlen($sql) - 1] = ' ';
                $sql .= '),';
            }
            $sql[strlen($sql) - 1] = ' ';
            $sql .= ' ON DUPLICATE KEY UPDATE ';
            $sql .= static::$primaryKey . ' = VALUES (' . static::$primaryKey . '),';
            foreach(static::$columns as $col) {
                $sql .= " ${col} = VALUES (${col}),";
            }

            $sql[strlen($sql) - 1] = ' ';
        } elseif(static::$driver == 'sqlsrv') {
            $sql = 'UPDATE ' . static::$tableName . ' SET ';
            foreach(static::$columns as $col) {
                $sql .= "${col} = CASE " . static::$primaryKey . ' ';
                foreach($objects as $object) {
                    $vars[] = $object->$col;
                    $sql .= 'WHEN ' . static::getFormatedValue($object->{static::$primaryKey}) 
                        . ' THEN ? ';
                }
                $sql .= 'END,';
            }
            $sql[strlen($sql) - 1] = ' ';
            $sql .= ' WHERE ' . static::$primaryKey . ' IN(';
            foreach($objects as $object) {
                $sql .= static::getFormatedValue($object->{static::$primaryKey}) . ',';
            }
            $sql[strlen($sql) - 1] = ')';
        }

        return [$sql, $vars];
    }

    public static function insertObjects(array $objects = []): void 
    {
        if(count($objects) > 0) {
            $vars = [];
            $sql = 'INSERT INTO ' . static::$tableName . ' ('
                . implode(',', static::$columns) . ') VALUES ';

            foreach($objects as $object) {
                $sql .= '(';
                foreach(static::$columns as $col) {
                    $vars[] = $object->$col;
                    $sql .= '?,';
                }
                $sql[strlen($sql) - 1] = ' ';
                $sql .= '),';
            }

            $sql[strlen($sql) - 1] = ' ';
            $connect = Connect::getInstance();
            $stmt = $connect->prepare($sql);
            $stmt->execute($vars);
        }
    }

    public static function deleteObjects(array $objects = []): void 
    {
        if(count($objects) > 0) {
            $sql = 'DELETE FROM ' . static::$tableName . 
                ' WHERE ' . static::$primaryKey . ' IN (';
            
            foreach($objects as $object) {
                $sql .= static::getFormatedValue($object->{static::$primaryKey}) . ',';
            }
            
            $sql[strlen($sql) - 1] = ')';
            $connect = Connect::getInstance();
            $connect->query($sql);
        }
    }

    public static function deleteAll(): void 
    {
        $sql = 'DELETE FROM ' . static::$tableName;
        $connect = Connect::getInstance();
        $connect->query($sql);
    }

    public static function getDataLayer(
        array $filters = [],
        array $joins = [],
        string $columns = '*', 
        int $limit = 10, 
        int $page = 1, 
        array $sorting = []
    )  
    {
        $offset = ($page - 1) * $limit;
        
        $class = get_called_class();
        $model = new $class();
        
        $finders = static::getFilters($filters);
        $order = static::getSorting($sorting);
        $entities = static::getJoins($joins);
        $model->join($entities)->find($finders[0], $finders[1], $columns);
        
        if(static::$driver == 'mysql') {
            $model->limit($limit)->offset($offset)->order($order);
        } elseif(static::$driver == 'sqlsrv') {
            $model->order($order . " OFFSET {$offset} ROWS FETCH NEXT {$limit} ROWS ONLY");
        }

        return $model;
    }

    public static function getDataLayerByRawSQL(string $statement, int $limit = 10, int $page = 1, array $sorting = []) 
    {
        $class = get_called_class();

        $offset = ($page - 1) * $limit;
        $order = static::getSorting($sorting);

        $model = new $class();
        $model->statement($statement);

        if(static::$driver == 'mysql') {
            $model->limit($limit)->offset($offset)->order($order);
        } elseif(static::$driver == 'sqlsrv') {
            $model->order($order . " OFFSET {$offset} ROWS FETCH NEXT {$limit} ROWS ONLY");
        }

        return $model;
    }

    protected static function executeSQL(string $sql) 
    {
        $connect = Connect::getInstance();
        $stmt = $connect->prepare($sql);
        $stmt->execute();
    }

    private static function getSearch(string $terms = '', array $columns = []): string 
    {
        $query = '';
        if($terms && $columns) {
            $words = explode(' ', $terms);
            $conds = array();
            $searches = array();
            $numCols = count($columns);
    
            foreach($words as $word) {
                $col = 1;
                foreach($columns as $column) {
                    $open = $col == 1 ? ' ( ' : '';
                    $close = $col == $numCols ? ' ) ' : '';
                    $conds[] = "{$open} {$column} LIKE '%" . $word . "%' {$close}";
                    $col++;
                }
                $searches[] = implode(' OR ', $conds);
                $conds = [];
                $col = 1;
            }
    
            $query .= implode(' AND ', $searches);
            return $query;
        } else {
            return '';
        }
    }

    private static function getSorting(array $sorting = []): string 
    {
        $sql = '';
        if(count($sorting) > 0) {
            foreach($sorting as $column => $value) {
                if($column == 'raw') {
                    $sql .= "{$value}";
                } else {
                    $sql .= "{$column} {$value},";
                }
            }

            if($sql) $sql[strlen($sql) - 1] = ' ';
            return $sql;
        }

        return '';
    }

    private static function getJoins(array $joins = []): string 
    {
        $sql = '';

        if(count($joins) > 0) {
            foreach($joins as $entity => $conditions) {
                $sql .= 'LEFT JOIN ' . $entity . ' ON ';
                foreach($conditions as $column => $value) {
                    if($column == 'raw') {
                        $sql .= "{$value} AND ";
                    } else {
                        $sql .= "{$entity}.{$column} = " . static::getFormatedValue($value) . ' AND ';
                    }
                }
                $sql = substr($sql, 0, -4);
            }
        }

        return $sql;
    }

    private static function getFilters(array $filters = []): array 
    {
        $terms = '';
        $params = '';
        $count = 0;

        if(count($filters) > 0) {
            foreach($filters as $column => $value) {
                $count++;
                if($column == 'raw') {
                    $terms .= "{$value} AND ";
                } elseif($column == 'search') {
                    if($value['term'] && $value['columns']) {
                        $terms .= static::getSearch($value['term'], $value['columns']) . ' AND ';
                    }
                } elseif($column == '>=' || $column == '<=' || $column == '<' || $column == '>') {
                    if($value) {
                        foreach($value as $col => $val) {
                            $terms .= "{$col} {$column} " . static::getFormatedValue($val) . ' AND ';
                        }
                    }
                } else {
                    $terms .= "{$column} = :param{$count} AND ";
                    $params .= "param{$count}={$value}&";
                }
            }

            if($terms) $terms = substr($terms, 0, -4);
            if($params) $params = substr($params, 0, -1);
        }

        return [$terms, $params];
    }

    private static function getFormatedValue($value) 
    {
        if(is_null($value)) {
            return 'null';
        } elseif(gettype($value) === 'string') {
            $value = html_entity_decode($value);
            return "'${value}'";
        } else {
            return $value;
        }
    }
}