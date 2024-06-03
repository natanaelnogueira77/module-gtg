<?php 

namespace GTG\MVC\Database;

use DateTime, PDO, PDOException;
use GTG\MVC\{ Application, Model };
use GTG\MVC\Database\{ ActiveRecordStatement, Database };
use GTG\MVC\Exceptions\ActiveRecordException;

abstract class ActiveRecord extends Model 
{
    protected const CREATED_AT_COLUMN_NAME = 'created_at';
    protected const UPDATED_AT_COLUMN_NAME = 'updated_at';
    protected const DELETED_AT_COLUMN_NAME = 'deleted_at';

    abstract public static function tableName(): string;
    abstract public static function attributes(): array;
    abstract public static function primaryKey(): string;
    abstract public static function hasTimestamps(): bool;

    private Database $database;
    protected array $values = [];
    
    public function __construct() 
    {
        $this->database = Application::getInstance()->database;
    }

    public function __get(string $key): mixed
    {
        return isset($this->values[$key]) ? $this->values[$key] : null;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->values[$key] = $value;
    }

    public static function get(string|array $columns = '*'): ActiveRecordStatement 
    {
        return new ActiveRecordStatement(static::class, static::tableName(), $columns);
    }

    public static function findById(int $id, string $columns = '*'): static|null
    {
        return static::get($columns)->filters(fn($where) => $where->equal('id')->assignment($id))->fetch();
    }

    protected function exec(string $sql): void  
    {
        $this->database->exec($sql);
    }

    public function attributesToArray(): array 
    {
        $attributes = [];
        foreach(static::attributes() as $attr) {
            $attributes[$attr] = $this->{$attr};
        }

        return $attributes;
    }

    public function columnsValuesToArray(): array 
    {
        return array_merge(
            [static::primaryKey() => $this->{static::primaryKey()}], 
            $this->attributesToArray(), 
            static::hasTimestamps() ? [
                static::CREATED_AT_COLUMN_NAME => $this->{static::CREATED_AT_COLUMN_NAME}, 
                static::UPDATED_AT_COLUMN_NAME => $this->{static::UPDATED_AT_COLUMN_NAME}
            ] : []
        );
    }

    public function fillAttributes(array $attributes): static 
    {
        foreach($attributes as $attr => $value) {
            if(in_array($attr, static::attributes())) $this->{$attr} = $value;
        }

        return $this;
    }

    public function save(): void 
    {
        $primary = static::primaryKey();

        try {
            if($id = $this->{$primary}) {
                $this->update("{$primary} = :id", "id={$id}");
            } else {
                $this->{static::primaryKey()} = $this->create();
            }
        } catch(PDOException $e) {
            throw new ActiveRecordException($e->getMessage());
        }
    }

    private function create(): string|bool
    {
        $attributes = $this->attributesToArray();

        if(static::hasTimestamps()) {
            $attributes[static::CREATED_AT_COLUMN_NAME] = (new DateTime('now'))->format('Y-m-d H:i:s');
            $attributes[static::UPDATED_AT_COLUMN_NAME] = (new DateTime('now'))->format('Y-m-d H:i:s');
        }

        $columns = implode(', ', array_keys($attributes));
        $values = ':' . implode(', :', array_keys($attributes));

        $stmt = $this->database->getConnection();
        $prepare = $stmt->prepare("INSERT INTO " . static::tableName() . " ({$columns}) VALUES ({$values})");
        $prepare->execute(static::formatAttributes($attributes));

        return $stmt->lastInsertId();
    }

    private static function formatAttributes(array $attributes): ?array
    {
        $formatted = [];
        foreach($attributes as $attr => $value) {
            $formatted[$attr] = is_null($value) ? null : filter_var($value, FILTER_DEFAULT);
        }
        return $formatted;
    }

    private function update(string $terms, string $params): void
    {
        $attributes = $this->columnsValuesToArray();
        if(static::hasTimestamps()) {
            $attributes[static::UPDATED_AT_COLUMN_NAME] = (new DateTime('now'))->format('Y-m-d H:i:s');
        }

        $dataSet = [];
        foreach($attributes as $bind => $value) {
            $dataSet[] = "{$bind} = :{$bind}";
        }
        $dataSet = implode(', ', $dataSet);
        parse_str($params ?? '', $params);

        $stmt = $this->database->getConnection();
        $prepare = $stmt->prepare("UPDATE " . static::tableName() . " SET {$dataSet} WHERE {$terms}");
        $prepare->execute(static::formatAttributes(array_merge($attributes, $params)));
    }

    public function destroy(): void
    {
        $primary = static::primaryKey();
        $id = $this->{$primary};

        if(empty($id)) return;

        $this->delete("{$primary} = :id", "id={$id}");
    }

    private function delete(string $terms, ?string $params): void
    {
        try {
            $stmt = $this->database->getConnection();
            $prepare = $stmt->prepare("DELETE FROM " . static::tableName() . " WHERE {$terms}");

            if($params) {
                parse_str($params, $params);
                $prepare->execute($params);
                return;
            }

            $prepare->execute();
        } catch(PDOException $e) {
            throw new ActiveRecordException($e->getMessage());
        }
    }

    public static function saveMany(array $models): array 
    {
        $modelsToBeCreated = [];
        $modelsToBeUpdated = [];
        
        foreach($models as $index => $model) {
            if($model->{static::primaryKey()}) {
                $modelsToBeUpdated[] = $model;
            } else {
                $modelsToBeCreated[] = $model;
            }
        }

        try {
            if($modelsToBeCreated) {
                $modelsToBeCreated = static::createMany($modelsToBeCreated);
            }

            if($modelsToBeUpdated) {
                $modelsToBeUpdated = static::updateMany($modelsToBeUpdated);
            }

            return array_merge($modelsToBeUpdated, $modelsToBeCreated);
        } catch(PDOException $e) {
            throw new ActiveRecordException($e->getMessage());
        }
    }

    private static function createMany(array $models): array 
    {
        $modelsData = [];
        $filterData = [];

        $now = (new DateTime('now'))->format('Y-m-d H:i:s');
        $hasTimestamps = static::hasTimestamps();

        foreach($models as $index => $model) {
            $modelsData[$index] = $model->attributesToArray();
            if($hasTimestamps) {
                $modelsData[$index][static::CREATED_AT_COLUMN_NAME] = $now;
                $modelsData[$index][static::UPDATED_AT_COLUMN_NAME] = $now;
            }
        }

        $attributes = static::attributes();
        $columns = implode(', ', $attributes);

        foreach($modelsData as $index => $modelData) {
            foreach($attributes as $attribute) {
                $filterData["{$attribute}{$index}"] = $modelData[$attribute];
            }
        }

        $values = implode(', ', array_map(function($data, $index) use ($attributes) {
            return '(' . implode(', ', array_map(fn($attr) => ":{$attr}{$index}", $attributes)) . ')';
        }, $modelsData, array_keys($modelsData)));

        $stmt = Application::getInstance()->database->getConnection();
        $prepare = $stmt->prepare("INSERT INTO " . static::tableName() . " ({$columns}) VALUES {$values}");
        $prepare->execute(static::formatAttributes($filterData));

        $firstId = $stmt->lastInsertId();
        foreach($models as $index => $model) {
            $models[$index]->{static::primaryKey()} = $firstId;
            $firstId++;
        }

        return $models;
    }

    private static function getAttributesAndTimestampsNames(): array 
    {
        return array_merge(static::attributes(), static::hasTimestamps() ? [
            static::CREATED_AT_COLUMN_NAME, 
            static::UPDATED_AT_COLUMN_NAME
        ] : []);
    }

    private static function updateMany(array $models): array 
    {
        $modelsData = [];
        $filterData = [];

        $now = (new DateTime('now'))->format('Y-m-d H:i:s');
        $hasTimestamps = static::hasTimestamps();

        foreach($models as $index => $model) {
            $modelsData[$index] = $model->columnsValuesToArray();
            if($hasTimestamps) {
                $modelsData[$index][static::UPDATED_AT_COLUMN_NAME] = $now;
            }
        }

        $attributes = static::getColumns();
        $dataSet = implode(', ', array_map(fn($attr) => "{$attr} = VALUES ({$attr})", $attributes));

        $columns = implode(', ', $attributes);
        foreach($modelsData as $index => $modelData) {
            foreach($attributes as $attribute) {
                $filterData["{$attribute}{$index}"] = $modelData[$attribute];
            }
        }

        $values = implode(', ', array_map(function($data, $index) use ($attributes) {
            return '(' . implode(', ', array_map(fn($attr) => ":{$attr}{$index}", $attributes)) . ')';
        }, $modelsData, array_keys($modelsData)));

        $stmt = Application::getInstance()->database->getConnection();
        $prepare = $stmt->prepare("
            INSERT INTO " . static::tableName() . " ({$columns}) VALUES {$values} 
            ON DUPLICATE KEY UPDATE {$dataSet}
        ");
        $prepare->execute(static::formatAttributes($filterData));

        return $models;
    }

    private static function getColumns(): array 
    {
        return array_merge([static::primaryKey()], static::getAttributesAndTimestampsNames());
    }
}