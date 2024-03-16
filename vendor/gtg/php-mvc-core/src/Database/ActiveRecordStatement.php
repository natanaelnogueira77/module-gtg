<?php 

namespace GTG\MVC\Database;

use DateTime;
use GTG\MVC\Application;
use GTG\MVC\Database\ActiveRecord;
use GTG\MVC\Database\ActiveRecordStatement;
use GTG\MVC\Database\Database;
use GTG\MVC\Exceptions\ActiveRecordException;
use GTG\MVC\Model;
use PDO;
use PDOException;

class ActiveRecordStatement 
{
    protected ?string $statement = null;
    protected ?array $params = null;
    protected ?string $group = null;
    protected ?string $order = null;
    protected ?string $limit = null;
    protected ?string $offset = null;
    private Database $database;
    
    public function __construct(
        protected string $className,
        string $tableName, 
        ?array $filters = [], 
        ?string $columns = '*',
        ?string $statement = null
    ) 
    {
        $this->database = Application::getInstance()->database;
        if(!$statement) {
            [$terms, $params] = $this->database->getDriver()->getFiltersForStatement($filters);
            $this->statement = "SELECT {$columns} FROM {$tableName}" . (
                $filters ? (" WHERE {$terms}") : ''
            );
            parse_str($params ?? '', $this->params);
        } else {
            $this->statement = $statement;
        }
    }

    public function statement(string $sql): static 
    {
        $this->statement = $sql;
        return $this;
    }

    public function group(string $column): static
    {
        $this->group = " GROUP BY {$column}";
        return $this;
    }

    public function order(string $orderRule): static
    {
        $this->order = " ORDER BY {$orderRule}";
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    public function in(string $column, array $values): static
    {
        $index = 0;
        $params = array();
        $statement = "{$column} IN (";

        foreach($values as $value) {
            $index++;
            if($value == end($values)) {
                $statement .= ":in_{$index}";
            } else {
                $statement .= ":in_{$index},";
            }

            $params["in_{$index}"] = $value;
        }

        $statement .= ')';
        if(!str_contains($this->statement, 'WHERE')) {
            $this->statement .= ' WHERE ' . $statement;
        } else {
            $this->statement .= ' AND ' . $statement;
        }

        $this->params = $this->params ? $this->params += $params : $params;
        return $this;
    }

    public function offset(int $offset): static 
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    public function paginate(int $limit = 10, int $page = 1): static
    {
        $this->limit($limit)->offset(($page - 1) * $limit);
        return $this;
    }

    public function fetch(bool $all = false): array|ActiveRecord|null
    {
        try {
            $stmt = $this->database->getConnection()->prepare(
                $this->statement . $this->group . $this->order . $this->limit . $this->offset
            );

            $stmt->execute($this->params);

            if(!$stmt->rowCount()) {
                return null;
            }

            if($all) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, $this->className);
            }

            return $stmt->fetchObject($this->className);
        } catch(PDOException $e) {
            throw new ActiveRecordException($e->getMessage());
        }
    }

    public function count(): int
    {
        try {
            $stmt = $this->database->getConnection()->prepare($this->statement);
            $stmt->execute($this->params);
            return $stmt->rowCount();
        } catch(PDOException $e) {
            throw new ActiveRecordException($e->getMessage());
        }
    }
}