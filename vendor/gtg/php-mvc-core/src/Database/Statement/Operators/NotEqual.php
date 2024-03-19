<?php 

namespace GTG\MVC\Database\Statement\Operators;

final class NotEqual extends SymbolOperator
{
    public function build(): string 
    {
        return "{$this->column} != {$this->assignment}";
    }
}