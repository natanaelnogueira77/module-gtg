<?php 

namespace Views\Components\DataTable;

use Views\Widget;

class Cell extends Widget 
{
    public function __construct(
        public readonly ?string $content = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/data-table/cell', ['view' => $this]);
    }
}