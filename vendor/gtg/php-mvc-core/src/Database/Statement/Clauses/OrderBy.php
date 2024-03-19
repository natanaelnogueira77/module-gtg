<?php 

namespace GTG\MVC\Database\Statement\Clauses;

final class OrderBy extends Clause 
{
    public function __construct(
        private string $expression
    )
    {}

    public function build(): string 
    {
        return 'ORDER BY ' . $this->expression;
    }
}