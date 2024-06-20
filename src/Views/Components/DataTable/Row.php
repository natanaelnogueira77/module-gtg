<?php 

namespace Views\Components\DataTable;

use Views\Widget;

class Row extends Widget 
{
    public function __construct(
        public readonly ?array $cells = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/data-table/row', ['view' => $this]);
    }

    public function getCells(): string 
    {
        return $this->cells ? implode('', $this->cells) : '';
    }
}