<?php 

namespace GTG\MVC\Database\Statement\Operators;

final class IsNull extends Operator
{
    public function build(): string 
    {
        return "{$this->column} IS NULL";
    }
}