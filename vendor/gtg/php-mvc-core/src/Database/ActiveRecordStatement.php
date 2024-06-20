<?php 

namespace GTG\MVC\Database;

use DateTime, PDO, PDOException;
use GTG\MVC\{ Application, Model };
use GTG\MVC\Database\Statement\Select;
use GTG\MVC\Database\Statement\Clauses\{ From, Limit, OrderBy, Where };
use GTG\MVC\Exceptions\StatementException;

final class ActiveRecordStatement 
{
    protected ?string $statement = null;
    protected ?string $filters = null;
    protected ?array $params = null;
    protected ?string $order = null;
    protected ?string $limit = null;
    private Database $database;
    
    public function __construct(
        protected string $className,
        string|array $tableReferences,
        string|array $columns = '*'
    ) 
    {
        $this->database = Application::getInstance()->database;
        $this->columns($columns);
        $this->tableReferences($tableReferences);
    }

    private function columns(string|array $columns = '*'): static 
    {
        $this->statement = (new Select($columns))->build();
        return $this;
    }

    private function tableReferences(string|array $tableReferences): static 
    {
        $this->statement .= ' ' . (new From($tableReferences))->build();
        return $this;
    }

    private function getQuery(): string 
    {
        return $this->statement . $this->filters . $this->order . $this->limit;
    }

    public function filters(callable $callback): static 
    {
        $where = new Where();
        call_user_func($callback, $where);
        $this->filters = ' ' . $where->build();
        return $this;
    }

    public function order(string $expression): static
    {
        $this->order = ' ' . (new OrderBy($expression))->build();
        return $this;
    }

    public function limit(int $limitCount, ?int $offset): static
    {
        $this->limit = ' ' . (new Limit($limitCount, $offset))->build();
        return $this;
    }

    public function paginate(int $limit = 10, int $page = 1): static
    {
        $this->limit($limit, ($page - 1) * $limit);
        return $this;
    }

    public function fetch(bool $all = false): array|object|null
    {
        try {
            $stmt = $this->database->getConnection()->prepare($this->getQuery());
            $stmt->execute($this->params);

            if(!$stmt->rowCount()) {
                return null;
            }

            if($all) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, $this->className);
            }

            return $stmt->fetchObject($this->className);
        } catch(PDOException $e) {
            throw new StatementException($e->getMessage() . $this->getQuery());
        }
    }

    public function count(): int
    {
        try {
            $stmt = $this->database->getConnection()->prepare($this->statement . $this->filters);
            $stmt->execute($this->params);
            return $stmt->rowCount();
        } catch(PDOException $e) {
            throw new StatementException($e->getMessage());
        }
    }
}