<?php 

namespace GTG\MVC\Database\Statement\Clauses;

final class GroupBy extends Clause 
{
    public function __construct(
        private string $expression
    )
    {}

    public function build(): string 
    {
        return 'GROUP BY ' . $this->expression;
    }
}