<?php 

namespace GTG\MVC\Database\Statement\Clauses;

use GTG\MVC\Database\Statement\Operators\{ 
    Between, 
    Equal, 
    GreaterThan, 
    GreaterThanOrEqual, 
    In,
    IsNotNull,
    IsNull,
    LessThan, 
    LessThanOrEqual, 
    Like, 
    NotBetween,
    NotEqual,
    NotIn,
    NotLike,
    Operator
};

trait Conditions 
{
    private bool $isInGroup = false;
    private ?array $currentGroup = null;

    public function groupWithAnd(callable $callback): array
    {
        return $this->group($callback, 'AND');
    }

    private function group(callable $callback, string $glue): array 
    {
        $this->isInGroup = true;
        call_user_func($callback, $this);
        $this->isInGroup = false;
        $operator = $this->addOperator([
            'operators' => $this->currentGroup,
            'glue' => $glue
        ]);
        $this->currentGroup = null;
        return $operator;
    }

    private function addOperator(Operator|array $operator): Operator|array 
    {
        if($this->isInGroup) {
            $this->currentGroup[] = $operator;
        } else {
            $this->operators[] = $operator;
        }

        return $operator;
    }

    public function groupWithOr(callable $callback): array
    {
        return $this->group($callback, 'OR');
    }

    public function equal(string $column): Equal
    {
        return $this->addOperator(new Equal($column));
    }

    public function notEqual(string $column): NotEqual
    {
        return $this->addOperator(new NotEqual($column));
    }

    public function greaterThan(string $column): GreaterThan
    {
        return $this->addOperator(new GreaterThan($column));
    }

    public function greaterThanOrEqual(string $column): GreaterThanOrEqual
    {
        return $this->addOperator(new GreaterThanOrEqual($column));
    }

    public function lessThan(string $column): LessThan
    {
        return $this->addOperator(new LessThan($column));
    }

    public function lessThanOrEqual(string $column): LessThanOrEqual
    {
        return $this->addOperator(new LessThanOrEqual($column));
    }

    public function between(string $column): Between 
    {
        return $this->addOperator(new Between($column));
    }

    public function notbetween(string $column): NotBetween 
    {
        return $this->addOperator(new NotBetween($column));
    }

    public function in(string $column): In 
    {
        return $this->addOperator(new In($column));
    }

    public function notIn(string $column): NotIn 
    {
        return $this->addOperator(new NotIn($column));
    }

    public function like(string $column): Like 
    {
        return $this->addOperator(new Like($column));
    }

    public function notLike(string $column): NotLike 
    {
        return $this->addOperator(new NotLike($column));
    }

    public function isNull(string $column): IsNull 
    {
        return $this->addOperator(new IsNull($column));
    }

    public function isNotNull(string $column): IsNotNull 
    {
        return $this->addOperator(new IsNotNull($column));
    }

    protected function getConditions(): string 
    {
        return implode(' AND ', array_map(fn($operator) => $this->buildCondition($operator), $this->operators));
    }

    private function buildCondition(Operator|array $operator): string 
    {
        if(is_array($operator)) {
            return '(' . implode(
                " {$operator['glue']} ", 
                array_map(fn($operator) => $this->buildCondition($operator), $operator['operators'])
            ) . ')';
        } else {
            return $operator->build();
        }
    }
}