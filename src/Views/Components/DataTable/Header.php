<?php 

namespace Views\Components\DataTable;

use Models\Lists\ActiveRecordList;
use Views\Widget;

class Header extends Widget 
{
    public ?ActiveRecordList $list = null;

    public function __construct(
        public ?array $heads = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/data-table/header', ['view' => $this]);
    }

    public function getHeads(): string 
    {
        if($this->heads) {
            for($i = 0; $i < count($this->heads); $i++) {
                $this->heads[$i]->setList($this->list);
            }
        }
        return $this->heads ? implode('', $this->heads) : '';
    }

    public function setList(ActiveRecordList $list): void
    {
        $this->list = $list;
    }
}