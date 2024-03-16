<?php

namespace Src\Models\Lists;

abstract class ActiveRecordList 
{
    private int $resultsCount = 0;

    public function __construct(
        private int $limit = 10,
        private int $pageToShow = 1,
        private string $orderBy = 'id',
        private string $orderType = 'ASC',
        private ?string $searchTerm = null
    ) 
    {}

    public function getLimit(): int 
    {
        return $this->limit;
    }

    public function getPageToShow(): int 
    {
        return $this->pageToShow;
    }

    public function getOrderBy(): string 
    {
        return $this->orderBy;
    }

    public function getOrderType(): string 
    {
        return $this->orderType;
    }

    public function getFilters(): array 
    {
        return [];
    }

    public function getColumns(): string 
    {
        return '*';
    }

    public function getResultsCount(): int 
    {
        return $this->resultsCount;
    }

    public function getTotalPages(): int 
    {
        return ceil($this->resultsCount / $this->limit);
    }

    public function setResultsCount(int $resultsCount): self 
    {
        $this->resultsCount = $resultsCount;
        return $this;
    }
}