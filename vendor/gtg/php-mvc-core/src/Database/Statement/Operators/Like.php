<?php 

namespace GTG\MVC\Database\Statement\Operators;

class Like extends Operator
{
    protected string $pattern = '';

    public function pattern(string|int|float $assignment, bool $isVariable = false): self
    {
        $this->pattern = self::formatValue($assignment, $isVariable);
        return $this;
    }

    public function build(): string 
    {
        return "{$this->column} LIKE {$this->pattern}";
    }
}