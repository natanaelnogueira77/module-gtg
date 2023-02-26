<?php

namespace Src\Models;

use Exception;
use CoffeeCode\DataLayer\Connect;
use CoffeeCode\DataLayer\DataLayer;
use PDO;
use Src\Exceptions\AppException;
use Src\Exceptions\LogException;

class Model extends DataLayer 
{
    protected static $tableName = '';
    protected static $primaryKey = 'id';
    protected static $columns = [];
    protected static $required = [];
    protected static $hasTimestamps = false;
    protected static $database = DB_INFO;
    protected static $jsonColumns = [];
    
    protected $values = [];
    protected ?string $joins = null;

    public function __construct() 
    {
        parent::__construct(
            static::$tableName, 
            static::$required, 
            static::$primaryKey, 
            static::$hasTimestamps, 
            static::$database
        );
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

    public function get(array $filters = [], string $columns = '*'): DataLayer 
    {
        $finders = static::getFilters($filters);
        return $this->find($finders[0], $finders[1], $columns);
    }

    public function getByIds(array $ids, string $columns = '*'): DataLayer 
    {
        $in = implode(',', $ids);
        return $this->find(static::$primaryKey . " IN ({$in})", null, $columns);
    }

    public static function getGroupedBy(array $objects = [], string $column = 'id'): ?array 
    {
        if(!$column) $column = static::$primaryKey;

        if($objects) {
            $grouped = [];
            foreach($objects as $object) {
                $grouped[$object->$column] = $object;
            }

            return $grouped;
        }
        return null;
    }
    
    public function join(string $entity, array $filters = []): static 
    {
        $sql = "JOIN {$entity} ON ";
        if(count($filters) > 0) {
            foreach($filters as $column => $value) {
                if($column == 'raw') {
                    $sql .= "{$value} AND ";
                }
            }
            $sql = substr($sql, 0, -4);
        }

        $this->joins .= $sql;
        return $this;
    }

    public function innerJoin(string $entity, array $filters = []): static 
    {
        $sql = "INNER JOIN {$entity} ON ";
        if(count($filters) > 0) {
            foreach($filters as $column => $value) {
                if($column == 'raw') {
                    $sql .= "{$value} AND ";
                }
            }
            $sql = substr($sql, 0, -4);
        }

        $this->joins .= $sql;
        return $this;
    }

    public function leftJoin(string $entity, array $filters = []): static 
    {
        $sql = "LEFT JOIN {$entity} ON ";
        if(count($filters) > 0) {
            foreach($filters as $column => $value) {
                if($column == 'raw') {
                    $sql .= "{$value} AND ";
                }
            }
            $sql = substr($sql, 0, -4);
        }

        $this->joins .= $sql;
        return $this;
    }

    public function rightJoin(string $entity, array $filters = []): static 
    {
        $sql = "RIGHT JOIN {$entity} ON ";
        if(count($filters) > 0) {
            foreach($filters as $column => $value) {
                if($column == 'raw') {
                    $sql .= "{$value} AND ";
                }
            }
            $sql = substr($sql, 0, -4);
        }

        $this->joins .= $sql;
        return $this;
    }

    public function find(?string $terms = null, ?string $params = null, string $columns = "*"): DataLayer
    {
        if(!$this->joins) {
            return parent::find($terms, $params, $columns);
        }

        if ($terms) {
            $this->statement = "SELECT {$columns} FROM " 
                . static::$tableName . " {$this->joins} WHERE {$terms}";
            parse_str($params ? $params : '', $this->params);
            return $this;
        }

        $this->statement = "SELECT {$columns} FROM " . static::$tableName . " {$this->joins}";
        return $this;
    }

    public function statement(string $sql): static 
    {
        $this->statement = $sql;
        return $this;
    }

    public function paginate(int $limit = 10, int $page = 1, array $sorting = []): static
    {
        $this->limit($limit)->offset(($page - 1) * $limit);
        return $this;
    }

    public function sort(array $sorting = []): static 
    {
        $this->order(static::getSorting($sorting));
        return $this;
    }

    public function fetch(bool $all = false): array|static|null
    {
        $result = parent::fetch($all);
        if($this->fail()) {
            throw new LogException(
                $this->fail()->getMessage(),
                _('Ocorreu um erro inesperado enquanto você executava essa ação! Informe ao administrador.')
            );
        }
        return $result;
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
                _('Ocorreu um erro inesperado enquanto você executava essa ação! Informe ao administrador.')
            );
        }

        $this->{static::$primaryKey} = $this->data->{static::$primaryKey};
        return $result;
    }

    private static function getUpsertSQL(array $objects = []): array 
    {
        $sql = '';
        $vars = [];

        $sql = 'INSERT INTO ' . static::$tableName . ' (' . static::$primaryKey . ','
            . implode(',', static::$columns) . (static::$hasTimestamps ? ',created_at,updated_at' : '') 
            . ') VALUES ';

        foreach($objects as $object) {
            $sql .= '(' . static::getFormatedValue($object->{static::$primaryKey}) . ',';
            foreach(static::$columns as $col) {
                $vars[] = $object->$col;
                $sql .= '?,';
            }

            if(static::$hasTimestamps) {
                if($object->created_at) {
                    $vars[] = $object->created_at;
                } else {
                    $vars[] = date('Y-m-d H:i:s');
                }
                $vars[] = date('Y-m-d H:i:s');
                $sql .= '?,?,';
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

        return [$sql, $vars];
    }

    public static function insertMany(array $objects = []): void 
    {
        if(count($objects) > 0) {
            $vars = [];
            $sql = 'INSERT INTO ' . static::$tableName 
                . ' (' . implode(',', static::$columns) 
                . (static::$hasTimestamps ? ',created_at,updated_at' : '') . ') VALUES ';

            foreach($objects as $object) {
                $sql .= '(';
                foreach(static::$columns as $col) {
                    $vars[] = $object->$col;
                    $sql .= '?,';
                }

                if(static::$hasTimestamps) {
                    $vars[] = date('Y-m-d H:i:s');
                    $vars[] = date('Y-m-d H:i:s');
                    $sql .= '?,?,';
                }
                
                $sql[strlen($sql) - 1] = ' ';
                $sql .= '),';
            }

            $sql[strlen($sql) - 1] = ' ';
            static::executeSQL($sql, $vars);
        }
    }

    public static function updateMany(array $objects = []): void 
    {
        if(count($objects) > 0) {
            [$sql, $vars] = static::getUpsertSQL($objects);
            static::executeSQL($sql, $vars);
        }
    }

    public static function deleteMany(array $objects = []): void 
    {
        if(count($objects) > 0) {
            $sql = 'DELETE FROM ' . static::$tableName . ' WHERE ' . static::$primaryKey . ' IN (';
            
            foreach($objects as $object) {
                $sql .= static::getFormatedValue($object->{static::$primaryKey}) . ',';
            }
            
            $sql[strlen($sql) - 1] = ')';
            static::executeSQL($sql);
        }
    }

    public static function deleteAll(): void 
    {
        static::executeSQL('DELETE FROM ' . static::$tableName);
    }

    public function decodeJSON(): static
    {
        if(self::$columns && self::$jsonColumns) {
            foreach(self::$columns as $col) {
                if(in_array($col, self::$jsonValues)) {
                    if(gettype($this->$col) === 'string') {
                        $this->$col = json_decode($this->$col, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }
                }
            }
        }

        return $this;
    }

    public function encodeJSON(): static
    {
        if(self::$columns && self::$jsonColumns) {
            foreach(self::$columns as $col) {
                if(in_array($col, self::$jsonColumns)) {
                    if(gettype($this->$col) === 'array') {
                        $this->$col = json_encode($this->$col, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }
                }
            }
        }

        return $this;
    }

    protected static function executeSQL(string $sql, array $vars = []): void
    {
        $connect = Connect::getInstance(static::$database);
        $stmt = $connect->prepare($sql);
        $stmt->execute($vars);
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
                } elseif(in_array($column, ['>=', '<=', '<', '>'])) {
                    if($value) {
                        foreach($value as $col => $val) {
                            $terms .= "{$col} {$column} " . static::getFormatedValue($val) . ' AND ';
                        }
                    }
                } elseif($column == 'in') {
                    if($value) {
                        foreach($value as $col => $values) {
                            foreach($values as $val) {
                                $val = static::getFormatedValue($val);
                            }
                            $in = implode(',', $values);
                            $terms .= "{$col} IN ({$in}) AND ";
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

    protected function belongsTo(
        string $model, 
        string $foreign, 
        string $key = 'id', 
        string $columns = '*'
    ) 
    {
        return (new $model())->get([$key => $this->$foreign], $columns)->fetch(false);
    }

    protected function belongsToMany(
        string $model, 
        string $pivot, 
        string $foreign1, 
        string $foreign2, 
        string $columns = '*',
        ?array $pivotColumns = null
    ): ?array
    {
        $pivots = (new $pivot())
            ->leftJoin($model::getTableName(), [
                'raw' => "{$model::getTableName()}.{$model::$primaryKey} = {$pivot::getTableName()}.{$foreign2}"
            ])
            ->get([$foreign1 => $this->{$pivot::$primaryKey}])
            ->fetch(true);
        if($pivots) {
            $objects = [];
            foreach($pivots as $pivot) {
                $object = new $model();
                $object->{$model::$primaryKey} = $pivot->$foreign2;
                foreach($model::$columns as $col) {
                    $object->$col = $pivot->$col;
                }

                if($model::$hasTimestamps) {
                    $object->created_at = $pivot->created_at;
                    $object->updated_at = $pivot->updated_at;
                }

                if($pivotColumns) {
                    foreach($pivotColumns as $col) {
                        $object->$col = $pivot->$col;
                    }
                }
                
                $objects[] = $object;
            }

            return $objects;
        }

        return null;
    }

    protected function hasOne(
        string $model, 
        string $foreign, 
        string $key = 'id', 
        string $columns = '*'
    )
    {
        return (new $model())->get([$foreign => $this->$key], $columns)->fetch(false);
    }

    protected function hasMany(
        string $model, 
        string $foreign, 
        string $key = 'id', 
        string $columns = '*'
    ): ?array
    {
        return (new $model())->get([$foreign => $this->$key], $columns)->fetch(true);
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