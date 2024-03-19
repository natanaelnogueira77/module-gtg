<?php 

namespace GTG\MVC\Database\Statement\Operators;

final class NotBetween extends Between
{
    public function build(): string 
    {
        return "{$this->column} NOT BETWEEN {$this->min} AND {$this->max}";
    }
}