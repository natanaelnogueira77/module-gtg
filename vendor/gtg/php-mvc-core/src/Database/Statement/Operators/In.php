<?php 

namespace GTG\MVC\Database\Statement\Operators;

class In extends Operator
{
    protected array $assignments = [];

    public function add(string|int|float $assignment, bool $isVariable = false): self
    {
        $this->assignments[] = self::formatValue($assignment, $isVariable);
        return $this;
    }

    public function build(): string 
    {
        return "{$this->column} IN (" . implode(',', $this->assignments) . ")";
    }
}