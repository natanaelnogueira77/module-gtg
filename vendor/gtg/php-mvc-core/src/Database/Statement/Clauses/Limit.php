<?php 

namespace GTG\MVC\Database\Statement\Clauses;

final class Limit extends Clause 
{
    public function __construct(
        private int $limitCount,
        private ?int $offset
    )
    {}

    public function build(): string 
    {
        return 'LIMIT ' . $this->limitCount . ($this->offset ? " OFFSET {$this->offset}" : '');
    }
}