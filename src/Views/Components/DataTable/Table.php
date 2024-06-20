<?php 

namespace Views\Components\DataTable;

use Models\Lists\ActiveRecordList;
use Views\Widget;

class Table extends Widget 
{
    private Pagination $pagination;
    public function __construct(
        public readonly ActiveRecordList $list,
        public readonly Header $header,
        public readonly Body $body
    ) 
    {
        $this->header->setList($this->list);
        $this->body->setHeadsCount(count($this->header->heads ?? []));
        $this->pagination = new Pagination(list: $list);
    }

    public function __toString(): string 
    {
        return $this->getContext()->render('components/data-table/table', ['view' => $this]);
    }

    public function getPagination(): Pagination 
    {
        return $this->pagination;
    }

    public function getHeader(): Header 
    {
        return $this->header;
    }

    public function getBody(): Body 
    {
        return $this->body;
    }
}