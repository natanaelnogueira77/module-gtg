<?php 

namespace GTG\MVC\Database\Statement\Clauses;

final class Where extends Clause 
{
    private array $operators = [];

    use Conditions;

    public function build(): string 
    {
        return $this->operators ? ('WHERE ' . $this->getConditions()) : '';
    }
}