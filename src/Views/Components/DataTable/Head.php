<?php 

namespace Views\Components\DataTable;

use Models\Lists\ActiveRecordList;
use Views\Widget;

class Head extends Widget 
{
    public ?ActiveRecordList $list = null;

    public function __construct(
        public readonly string $columnName,
        public readonly ?string $content = null,
        public readonly bool $isSortable = false
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/data-table/head', ['view' => $this]);
    }

    public function setList(ActiveRecordList $list): void
    {
        $this->list = $list;
    }
}