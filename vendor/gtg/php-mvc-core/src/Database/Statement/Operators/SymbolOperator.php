<?php 

namespace GTG\MVC\Database\Statement\Operators;

abstract class SymbolOperator extends Operator
{
    protected string|int|float|null $assignment = '';
    
    public function __construct(
        protected string $column
    )
    {}

    abstract public function build(): string;

    public function assignment(string|int|float $assignment, bool $isVariable = false): self
    {
        $this->assignment = self::formatValue($assignment, $isVariable);
        return $this;
    }

    protected static function formatValue(mixed $value, bool $isVariable = false): string 
    {
        if(is_null($value)) {
            return 'null';
        } elseif(gettype($value) === 'string' && !$isVariable) {
            return "'" . html_entity_decode($value) . "'";
        } else {
            return $value;
        }
    }
}