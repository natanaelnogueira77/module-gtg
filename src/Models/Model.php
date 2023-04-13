<?php

namespace Src\Models;

use CoffeeCode\DataLayer\Connect;
use CoffeeCode\DataLayer\DataLayer;
use Src\Exceptions\LogException;
use Src\Exceptions\ValidationException;

class Model extends DataLayer 
{
    protected static $tableName = '';
    protected static $primaryKey = 'id';
    protected static $columns = [];
    protected static $required = [];
    protected static $hasTimestamps = false;
    protected static $database = DB_INFO;
    protected static $metaInfo = [];
    
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

    public function setValues(array $values = []): static 
    {
        foreach($values as $column => $value) {
            $this->$column = $value;
            $this->values[$column] = $value;
        }
        return $this;
    }

    public static function getTableName(): string 
    {
        return static::$tableName;
    }

    public static function getPropertyValues(array $objects, string $property = 'id'): array 
    {
        return array_map(
            function ($o) use ($property) { return $o->$property; }, 
            array_filter($objects, function ($e) use ($property) { return !is_null($e->$property); })
        );
    }

    public static function getGroupedBy(array $objects, string $column = 'id', bool $multiple = false): ?array 
    {
        $groups = [];
        if($multiple) {
            foreach($objects as $object) {
                $groups[$object->$column][] = $object;
            }
        } else {
            foreach($objects as $object) {
                $groups[$object->$column] = $object;
            }
        }
        return $groups ? $groups : null;
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

        if($result) {
            if($all) {
                foreach($result as $object) {
                    $object->decode();
                }
            } else {
                $result->decode();
            }
        }

        return $result;
    }

    public function encode(): static 
    {
        return $this;
    }
    
    public function decode(): static 
    {
        return $this;
    }

    public function save(): bool 
    {
        $this->validate();
        $this->encode();
        
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

        $this->decode();
        $this->{static::$primaryKey} = $this->data->{static::$primaryKey};
        return $result;
    }

    public static function insertMany(array $objects): bool 
    {
        $vars = [];
        $sql = 'INSERT INTO ' . static::$tableName . ' (' . implode(',', static::$columns) 
            . (static::$hasTimestamps ? ',created_at,updated_at' : '') . ') VALUES ';

        foreach($objects as $object) {
            $object->encode();
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
        return static::executeSQL($sql, $vars);
    }

    public static function updateMany(array $objects): bool 
    {
        $vars = [];
        $sql = 'INSERT INTO ' . static::$tableName . ' (' . static::$primaryKey . ','
            . implode(',', static::$columns) . (static::$hasTimestamps ? ',created_at,updated_at' : '') 
            . ') VALUES ';

        foreach($objects as $object) {
            $object->encode();
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
        return static::executeSQL($sql, $vars);
    }

    public static function deleteMany(array $objects): bool 
    {
        $sql = 'DELETE FROM ' . static::$tableName . ' WHERE ' . static::$primaryKey . ' IN (';

        foreach($objects as $object) {
            $sql .= static::getFormatedValue($object->{static::$primaryKey}) . ',';
        }
        
        $sql[strlen($sql) - 1] = ')';
        return static::executeSQL($sql);
    }

    public static function deleteAll(): bool 
    {
        return static::executeSQL('DELETE FROM ' . static::$tableName);
    }

    protected static function executeSQL(string $sql, array $vars = []): bool
    {
        $connect = Connect::getInstance(static::$database);
        $stmt = $connect->prepare($sql);
        return $stmt->execute($vars);
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
                            $in = implode(',', array_map(function ($e) { return static::getFormatedValue($e); }, $values));
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

    protected function hasOne(string $model, string $foreign, string $key = 'id', string $columns = '*'): DataLayer 
    {
        return (new $model())->get([$foreign => $this->$key], $columns);
    }

    protected function hasMany(string $model, string $foreign, string $key = 'id', array $filters = [], string $columns = '*'): DataLayer
    {
        return (new $model())->get([$foreign => $this->$key] + $filters, $columns);
    }

    protected function belongsTo(string $model, string $foreign, string $key = 'id', string $columns = '*'): DataLayer 
    {
        return (new $model())->get([$key => $this->$foreign], $columns);
    }

    protected function belongsToMany(
        string $model, 
        string $pivotModel, 
        string $foreign1, 
        string $foreign2, 
        string $pivotProperty, 
        string $key1 = 'id',
        string $key2 = 'id',
        array $filters = [],
        string $columns = '*', 
        string $pivotColumns = '*'
    ): ?array
    {
        $pivots = (new $pivotModel())->get([$foreign1 => $this->$key1], $pivotColumns)->fetch(true);
        if($pivots) {
            $pivots = $pivotModel::getGroupedBy($pivots, $foreign2);
            $ids = $pivotModel::getPropertyValues($pivots, $foreign2);
            $objects = (new $model())->get(['in' => [$key2 => $ids]] + $filters, $columns)->fetch(true);
        }

        return $objects ? array_map(function ($o) use ($pivots, $pivotProperty, $key2) { $o->$pivotProperty = $pivots[$o->$key2]; return $o; }, $objects) : null;
    }

    protected static function withHasOne(
        array $objects, 
        string $model, 
        string $foreign, 
        string $property, 
        string $key = 'id', 
        array $filters = [], 
        string $columns = '*'
    ): array 
    {
        $ids = static::getPropertyValues($objects, $key);

        $registries = (new $model())->get(['in' => [$foreign => $ids]] + $filters, $columns)->fetch(true);
        if($registries) {
            $registries = $model::getGroupedBy($registries, $foreign);
            foreach($objects as $index => $object) {
                $objects[$index]->$property = $registries[$object->$key];
            }
        }

        return $objects;
    }

    protected static function withHasMany(
        array $objects, 
        string $model, 
        string $foreign, 
        string $property, 
        string $key = 'id', 
        array $filters = [], 
        string $columns = '*'
    ): array 
    {
        $ids = static::getPropertyValues($objects, $key);

        $registries = (new $model())->get(['in' => [$foreign => $ids]] + $filters, $columns)->fetch(true);
        if($registries) {
            $registries = $model::getGroupedBy($registries, $foreign, true);
            foreach($objects as $index => $object) {
                $objects[$index]->$property = $registries[$object->$key];
            }
        }

        return $objects;
    }

    protected static function withBelongsTo(
        array $objects, 
        string $model, 
        string $foreign, 
        string $property, 
        string $key = 'id', 
        array $filters = [], 
        string $columns = '*'
    ): array 
    {
        $ids = static::getPropertyValues($objects, $foreign);

        $registries = (new $model())->get(['in' => [$key => $ids]] + $filters, $columns)->fetch(true);
        if($registries) {
            $registries = $model::getGroupedBy($registries);
            foreach($objects as $index => $object) {
                $objects[$index]->$property = $registries[$object->$foreign];
            }
        }

        return $objects;
    }

    protected static function withBelongsToMany(
        array $objects, 
        string $model, 
        string $pivotModel, 
        string $foreign1, 
        string $foreign2, 
        string $property, 
        string $pivotProperty, 
        string $key1 = 'id',
        string $key2 = 'id',
        array $filters = [],
        string $columns = '*', 
        string $pivotColumns = '*'
    ): array 
    {
        $ids = static::getPropertyValues($objects, $key1);

        $pivots = (new $pivotModel())->get(['in' => [$key1 => $ids]], $pivotColumns)->fetch(true);
        if($pivots) {
            $groupedPivots = $pivotModel::getGroupedBy($pivots, $foreign1, true);
            $ids = $pivotModel::getPropertyValues($pivots, $foreign2);

            $registries = (new $model())->get(['in' => [$key2 => $ids]] + $filters, $columns)->fetch(true);
            $registries = $model::getGroupedBy($registries);

            $groupedRegistries = [];
            foreach($groupedPivots as $groupedPivot) {
                foreach($groupedPivot as $pivot) {
                    if(isset($registries[$pivot->$foreign2])) {
                        $registries[$pivot->$foreign2]->$pivotProperty = $pivot;
                        $groupedRegistries[$pivot->$foreign1][] = $registries[$pivot->$foreign2];
                    }
                }
            }

            foreach($objects as $index => $object) {
                $objects[$index]->$property = $groupedRegistries[$object->$key2];
            }
        }

        return $objects;
    }

    public function getMeta(string $meta): mixed
    {
        if(!static::$metaInfo) {
            return null;
        }

        $filters = [];
        if(static::$metaInfo['entity']) {
            $filters[static::$metaInfo['entity']] = $this->{static::$primaryKey};
        }
        $filters[static::$metaInfo['meta']] = $meta;
        $object = (new static::$metaInfo['class']())->get($filters)->fetch(false);
        return $object ? $object->{static::$metaInfo['value']} : null;
    }

    public function getGroupedMetas(array $metas): ?array
    {
        if(!static::$metaInfo) {
            return null;
        }

        $filters = [];
        if(static::$metaInfo['entity']) {
            $filters[static::$metaInfo['entity']] = $this->{static::$primaryKey};
        }
        $filters['in'] = [static::$metaInfo['meta'] => $metas];
        $objects = (new static::$metaInfo['class']())->get($filters)->fetch(true);

        if($objects) {
            $metas = [];
            foreach($objects as $object) {
                $metas[$object->{static::$metaInfo['meta']}] = $object->{static::$metaInfo['value']};
            }
            return $metas;
        }

        return null;
    }

    public function saveMeta(string $meta, mixed $value): bool 
    {
        if(!static::$metaInfo) {
            return false;
        }

        $filters = [];
        if(static::$metaInfo['entity']) {
            $filters[static::$metaInfo['entity']] = $this->{static::$primaryKey};
        }
        $filters[static::$metaInfo['meta']] = $meta;

        $object = (new static::$metaInfo['class']())->get($filters)->fetch(false);
        if(!$object) {
            $object = (new static::$metaInfo['class']());
            $object->{static::$metaInfo['entity']} = $this->{static::$primaryKey};
            $object->{static::$metaInfo['meta']} = $meta;
        }

        $object->{static::$metaInfo['value']} = $value;
        return $object->save();
    }

    public function saveManyMetas(array $data): bool 
    {
        if(!static::$metaInfo) {
            return false;
        }

        $filters = [];
        if(static::$metaInfo['entity']) {
            $filters[static::$metaInfo['entity']] = $this->{static::$primaryKey};
        }
        $filters['in'] = [static::$metaInfo['meta'] => array_keys($data)];

        $objects = (new static::$metaInfo['class']())->get($filters)->fetch(true);
        if($objects) {
            $objects = static::$metaInfo['class']::getGroupedBy($objects, static::$metaInfo['meta']);
        }

        $errors = [];

        foreach($data as $meta => $value) {
            if(isset($objects[$meta])) {
                $objects[$meta]->{static::$metaInfo['value']} = $value;
            } else {
                $objects[$meta] = (new static::$metaInfo['class']())->setValues([
                    static::$metaInfo['entity'] => $this->{static::$primaryKey},
                    static::$metaInfo['meta'] => $meta,
                    static::$metaInfo['value'] => $value
                ]);
            }
            
            try {
                $objects[$meta]->validate();
                $objects[$meta]->encode();
            } catch(ValidationException $e) {
                $errors = array_merge($errors, $e->getErrors());
            }
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
        }

        for($i = 0; $i <= count($objects) - 1; $i += 1000) {
            if($objects) {
                static::$metaInfo['class']::updateMany(array_slice($objects, $i, 1000));
            }
        }

        return true;
    }

    protected function validate(): void 
    {}

    private static function getFormatedValue(mixed $value): mixed 
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