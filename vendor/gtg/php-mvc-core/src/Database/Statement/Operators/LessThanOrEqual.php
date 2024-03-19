<?php 

namespace GTG\MVC\Database\Statement\Operators;

final class LessThanOrEqual extends SymbolOperator
{
    public function build(): string 
    {
        return "{$this->column} <= {$this->assignment}";
    }
}