<?php 

namespace GTG\MVC\Database\Statement\Operators;

final class IsNotNull extends Operator
{
    public function build(): string 
    {
        return "{$this->column} IS NOT NULL";
    }
}