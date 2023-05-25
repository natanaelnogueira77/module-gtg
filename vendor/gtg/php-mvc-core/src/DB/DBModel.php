<?php

namespace GTG\MVC\DB;

use CoffeeCode\DataLayer\DataLayer;
use GTG\MVC\Application;

abstract class DBModel extends DataLayer 
{
    public const RULE_EMAIL = 'email';
    public const RULE_INT = 'int';
    public const RULE_MATCH = 'match';
    public const RULE_MAX = 'max';
    public const RULE_MIN = 'min';
    public const RULE_REQUIRED = 'required';

    protected static $required = [];
    protected static $hasTimestamps = false;
    public array $errors = [];
    
    protected $values = [];
    protected ?string $joins = null;

    public function __construct() 
    {
        parent::__construct(
            $this->tableName(), 
            static::$required, 
            $this->primaryKey(), 
            static::$hasTimestamps, 
            Application::$DB_INFO
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

    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    abstract public function rules(): array;

    public function getData(): array 
    {
        foreach($this->attributes() as $attr) {
            $this->values[$attr] = $this->data->$attr;
        }
        return $this->values;
    }

    public function loadData(array $data): static 
    {
        foreach($data as $attr => $value) {
            if(in_array($attr, $this->attributes())) {
                $this->{$attr} = $value;
                $this->values[$attr] = $value;
            }
        }
        return $this;
    }

    public static function getPropertyValues(array $objects, string $property = 'id'): array 
    {
        return array_map(
            function ($o) use ($property) { return $o->$property; }, 
            array_filter($objects, function ($e) use ($property) { return !is_null($e->$property); })
        );
    }

    public static function getGroupedBy(array $objects, string $attribute = 'id', bool $multiple = false): ?array 
    {
        $groups = [];
        if($multiple) {
            foreach($objects as $object) {
                $groups[$object->$attribute][] = $object;
            }
        } else {
            foreach($objects as $object) {
                $groups[$object->$attribute] = $object;
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
        return $this->find($this->primaryKey() . ' IN (' . implode(',', $ids) . ')', null, $columns);
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

    public function find(?string $terms = null, ?string $params = null, string $columns = '*'): DataLayer
    {
        if(!$this->joins) {
            return parent::find($terms, $params, $columns);
        }

        if ($terms) {
            $this->statement = "SELECT {$columns} FROM {$this->tableName()} {$this->joins} WHERE {$terms}";
            parse_str($params ? $params : '', $this->params);
            return $this;
        }

        $this->statement = "SELECT {$columns} FROM " . $this->tableName() . " {$this->joins}";
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

    public function metaTableData(): ?array 
    {
        return null;
    }

    public function save(): bool 
    {
        if(!$this->validate()) {
            return false;
        }

        $this->encode();
        
        foreach($this->attributes() as $attr) {
            if(!is_null($this->$attr)) {
                $this->$attr = html_entity_decode($this->$attr);
            } else {
                unset($this->$attr);
            }
        }

        $result = parent::save();
        if($this->fail()) {
            return false;
        }

        $this->decode();
        $this->{$this->primaryKey()} = $this->data->{$this->primaryKey()};
        return $result;
    }

    /* public static function insertMany(array $objects): bool 
    {
        if(count($objects) === 0) {
            return false;
        }

        $vars = [];
        $sql = 'INSERT INTO ' . $this->tableName() . ' (' . implode(',', $this->attributes()) . ') VALUES ';

        $errors = false;
        foreach($objects as $object) {
            if($object->validate()) {
                $object->encode();
                $sql .= '(';
                foreach($this->attributes() as $col) {
                    $vars[] = $object->$col;
                    $sql .= '?,';
                }
                $object->decode();
                
                $sql[strlen($sql) - 1] = ' ';
                $sql .= '),';
            } else {
                $errors = true;
            }
        }

        if($errors) {
            return false;
        }

        $sql[strlen($sql) - 1] = ' ';
        $stmt = Application::$app->db->prepare($sql);
        return $stmt->execute($vars);
    } */

    public function saveMany(array $objects): bool 
    {
        if(count($objects) === 0) {
            return false;
        }

        $vars = [];
        $sql = 'INSERT INTO ' . $this->tableName() . ' (' . $this->primaryKey() . ',' 
            . implode(',', $this->attributes()) . ') VALUES ';

        $errors = false;
        foreach($objects as $object) {
            if($object->validate()) {
                $object->encode();
                $sql .= '(' . static::getFormatedValue($object->{$this->primaryKey()}) . ',';
                foreach($this->attributes() as $attr) {
                    $vars[] = $object->$attr;
                    $sql .= '?,';
                }
                $object->decode();

                $sql[strlen($sql) - 1] = ' ';
                $sql .= '),';
            } else {
                $errors = true;
            }
        }

        if($errors) {
            return false;
        }

        $sql[strlen($sql) - 1] = ' ';
        $sql .= ' ON DUPLICATE KEY UPDATE ';
        $sql .= $this->primaryKey() . ' = VALUES (' . $this->primaryKey() . '),';

        foreach($this->attributes() as $attr) {
            $sql .= " ${attr} = VALUES (${attr}),";
        }

        $sql[strlen($sql) - 1] = ' ';
        $stmt = Application::$app->db->prepare($sql);
        return $stmt->execute($vars);
    }

    public static function deleteMany(array $objects): bool 
    {
        $sql = "DELETE FROM {$this->tableName()} WHERE {$this->primaryKey()} IN (";

        foreach($objects as $object) {
            $sql .= static::getFormatedValue($object->{$this->primaryKey()}) . ',';
        }
        
        $sql[strlen($sql) - 1] = ')';

        $stmt = Application::$app->db->prepare($sql);
        return $stmt->execute($vars);
    }

    public static function deleteAll(): bool 
    {
        return Application::$app->db->exec("DELETE FROM {$this->tableName()}");
    }

    private static function getSearch(string $terms = '', array $attributes = []): string 
    {
        $query = '';
        if($terms && $attributes) {
            $words = explode(' ', $terms);
            $conds = array();
            $searches = array();
            $numCols = count($attributes);
    
            foreach($words as $word) {
                $col = 1;
                foreach($attributes as $attr) {
                    $open = $col == 1 ? ' ( ' : '';
                    $close = $col == $numCols ? ' ) ' : '';
                    $conds[] = "{$open} {$attr} LIKE '%" . $word . "%' {$close}";
                    $col++;
                }
                $searches[] = implode(' OR ', $conds);
                $conds = [];
                $col = 1;
            }
    
            $query .= implode(' AND ', $searches);
        }
        return $query;
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

        $pivots = (new $pivotModel())->get(['in' => [$foreign1 => $ids]], $pivotColumns)->fetch(true);
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
        $metaInfo = $this->metaTableData();
        if(!$metaInfo || !$metaInfo['class'] || !$metaInfo['meta'] || !$metaInfo['value'] || !$meta) {
            return null;
        }

        $filters = [];
        if($metaInfo['entity']) {
            $filters[$metaInfo['entity']] = $this->{$this->primaryKey()};
        }
        $filters[$metaInfo['meta']] = $meta;

        $object = (new $metaInfo['class']())->get($filters)->fetch(false);
        return $object ? $object->{$metaInfo['value']} : null;
    }

    public function getGroupedMetas(array $metas): ?array
    {
        $metaInfo = $this->metaTableData();
        if(!$metaInfo || !$metaInfo['class'] || !$metaInfo['meta'] || !$metaInfo['value'] || !$metas) {
            return null;
        }

        $filters = [];
        if($metaInfo['entity']) {
            $filters[$metaInfo['entity']] = $this->{$this->primaryKey()};
        }
        $filters['in'] = [$metaInfo['meta'] => $metas];
        
        $objects = (new $metaInfo['class']())->get($filters)->fetch(true);
        if($objects) {
            $metas = [];
            foreach($objects as $object) {
                $metas[$object->{$metaInfo['meta']}] = $object->{$metaInfo['value']};
            }
            return $metas;
        }

        return null;
    }

    public function saveMeta(string $meta, mixed $value): bool 
    {
        $metaInfo = $this->metaTableData();
        if(!$metaInfo || !$metaInfo['class'] || !$metaInfo['meta'] || !$metaInfo['value'] || !$meta) {
            return false;
        }

        $filters = [];
        if($metaInfo['entity']) {
            $filters[$metaInfo['entity']] = $this->{$this->primaryKey()};
        }
        $filters[$metaInfo['meta']] = $meta;

        $object = (new $metaInfo['class']())->get($filters)->fetch(false);
        if(!$object) {
            $object = (new $metaInfo['class']());
            if($metaInfo['entity']) {
                $object->{$metaInfo['entity']} = $this->{$this->primaryKey()};
            }
            $object->{$metaInfo['meta']} = $meta;
        }

        $object->{$metaInfo['value']} = $value;
        return $object->save();
    }

    public function saveManyMetas(array $data): bool 
    {
        $metaInfo = $this->metaTableData();
        if(!$metaInfo || !$metaInfo['class'] || !$metaInfo['meta'] || !$metaInfo['value'] || !$data) {
            return false;
        }

        $filters = [];
        if($metaInfo['entity']) {
            $filters[$metaInfo['entity']] = $this->{$this->primaryKey()};
        }
        $filters['in'] = [$metaInfo['meta'] => array_keys($data)];

        $objects = (new $metaInfo['class']())->get($filters)->fetch(true);
        if($objects) {
            $objects = $metaInfo['class']::getGroupedBy($objects, $metaInfo['meta']);
        }

        foreach($data as $meta => $value) {
            if(isset($objects[$meta])) {
                $objects[$meta]->{$metaInfo['value']} = $value;
            } else {
                $objects[$meta] = (new $metaInfo['class']())->setValues([
                    $metaInfo['entity'] => $this->{$this->primaryKey()},
                    $metaInfo['meta'] => $meta,
                    $metaInfo['value'] => $value
                ]);
            }
        }

        return $metaInfo['class']::saveMany($objects);
    }

    public function validate(): bool
    {
        foreach($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach($rules as $rule) {
                $ruleName = $rule;
                if(!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }

                if($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED, $rule);
                }

                if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, self::RULE_EMAIL, $rule);
                }

                if($ruleName === self::RULE_INT && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addErrorForRule($attribute, self::RULE_INT, $rule);
                }
                
                if($ruleName === self::RULE_MIN && $value && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }

                if($ruleName === self::RULE_MAX && $value && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }

                if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
            }
        }

        return empty($this->errors);
    }

    private function addErrorForRule(string $attribute, string $rule, array $params = []): void
    {
        $message = $params['message'] ?? '';
        foreach($params as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message): static 
    {
        $this->errors[$attribute][] = $message;
        return $this;
    }

    public function hasError(string $attribute): array|false
    {
        return $this->errors[$attribute] ?? false;
    }

    public function hasErrors(): bool 
    {
        return $this->errors ? true : false;
    }

    public function getFirstError(string $attribute): string|false
    {
        return $this->errors[$attribute][0] ?? false;
    }

    public function getFirstErrors(): array
    {
        $errors = [];
        foreach($this->errors as $attr => $messages) {
            $errors[$attr] = $messages[0];
        }
        return $errors;
    }

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