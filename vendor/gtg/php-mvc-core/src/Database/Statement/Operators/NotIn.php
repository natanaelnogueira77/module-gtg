<?php 

namespace GTG\MVC\Database\Statement\Operators;

final class NotIn extends In
{
    public function build(): string 
    {
        return "{$this->column} NOT IN (" . implode(',', $this->assignments) . ")";
    }
}