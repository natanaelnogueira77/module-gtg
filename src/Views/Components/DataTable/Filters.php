<?php 

namespace Views\Components\DataTable;

use Views\Widget;

class Filters extends Widget 
{
    public function __construct(
        public readonly string $formId
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/data-table/filters', ['view' => $this]);
    }
}