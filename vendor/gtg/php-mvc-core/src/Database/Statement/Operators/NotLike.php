<?php 

namespace GTG\MVC\Database\Statement\Operators;

final class NotLike extends Like
{
    public function build(): string 
    {
        return "{$this->column} NOT LIKE {$this->pattern}";
    }
}