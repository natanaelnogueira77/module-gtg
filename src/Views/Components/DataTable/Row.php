<?php 

namespace Src\Views\Components\DataTable;

class Row
{
    public function __construct(
        private array $cells = []
    ) 
    {}

    public function getCells(): array 
    {
        return $this->cells;
    }
}