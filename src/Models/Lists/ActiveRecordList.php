<?php

namespace Src\Models\Lists;

use Closure;

abstract class ActiveRecordList 
{
    protected int $resultsCount = 0;

    public function __construct(
        protected int $limit = 10,
        protected int $pageToShow = 1,
        protected string $orderBy = 'id',
        protected string $orderType = 'ASC',
        protected ?string $searchTerm = null
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

    public function getSearchTerm(): ?string 
    {
        return $this->searchTerm;
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