<?php 

namespace GTG\MVC\Database\Statement\Operators;

final class GreaterThanOrEqual extends SymbolOperator
{
    public function build(): string 
    {
        return "{$this->column} >= {$this->assignment}";
    }
}