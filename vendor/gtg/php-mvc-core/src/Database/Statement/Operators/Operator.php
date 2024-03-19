<?php 

namespace GTG\MVC\Database\Statement\Operators;

abstract class Operator
{
    public function __construct(
        protected string $column
    )
    {}

    abstract public function build(): string;

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