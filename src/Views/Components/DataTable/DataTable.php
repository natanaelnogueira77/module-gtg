<?php 

namespace Src\Views\Components\DataTable;

use Src\Models\Lists\ActiveRecordList;

class DataTable 
{
    private Pagination $pagination;

    public function __construct(
        private ActiveRecordList $activeRecordList,
        private Header $tableHeader,
        private Body $tableBody,
        private ?Filters $filters = null
    ) 
    {
        $this->tableHeader->setSelectedColumnId($this->activeRecordList->getOrderBy());
        $this->tableHeader->setSelectedOrderType($this->activeRecordList->getOrderType());
        $this->pagination = new Pagination(
            selectedPage: $this->activeRecordList->getPageToShow(),
            limit: $this->activeRecordList->getLimit(),
            totalRows: $this->activeRecordList->getResultsCount(), 
            totalPages: $this->activeRecordList->getTotalPages()
        );
    }

    public function getStyles(): string 
    {
        return 'align-middle mb-0 table table-borderless table-striped table-hover';
    }

    public function getPagination(): Pagination 
    {
        return $this->pagination;
    }

    public function getTableHeader(): Header 
    {
        return $this->tableHeader;
    }

    public function getTableBody(): Body 
    {
        return $this->tableBody;
    }

    public function getFilters(): ?Filters 
    {
        return $this->filters;
    }

    public function hasFilters(): bool 
    {
        return $this->filters ? true : false;
    }
}