<?php 

namespace GTG\MVC\Database\Statement\Operators;

class Between extends Operator
{
    protected string $min = '';
    protected string $max = '';

    public function min(string|int|float $assignment, bool $isVariable = false): self
    {
        $this->min = self::formatValue($assignment, $isVariable);
        return $this;
    }
    
    public function max(string|int|float $assignment, bool $isVariable = false): self
    {
        $this->max = self::formatValue($assignment, $isVariable);
        return $this;
    }

    public function build(): string 
    {
        return "{$this->column} BETWEEN {$this->min} AND {$this->max}";
    }
}