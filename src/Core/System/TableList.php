<?php

namespace Src\Core\System;

use Exception;
use Src\Core\Exceptions\LogException;
use Src\Core\System\DataTable;

class TableList 
{
    private $page;
    private $orderKey;
    private $orderType;
    private $limit;
    private $columns;
    private $search;
    private $searchColumns;
    private $filters;
    private $joins;
    private $lastPage;
    private $className;
    private $statement;
    private $result;

    public function __construct()
    {
        $this->page = 1;
        $this->limit = 10;
        $this->orderKey = 'id';
        $this->orderType = 'ASC';
        $this->filters = [];
        $this->joins = [];
        $this->searchColumns = [];
        $this->columns = '*';
    }

    public function setPage(int $page): TableList
    {
        $this->page = $page;
        return $this;
    }

    public function setOrder(string $key, string $order = 'ASC'): TableList 
    {
        $this->orderKey = $key;
        $this->orderType = $order;
        return $this;
    }

    public function setLimit(int $limit = 10): TableList 
    {
        $this->limit = $limit;
        return $this;
    }

    public function setColumns(string $columns = '*'): TableList 
    {
        $this->columns = $columns;
        return $this;
    }

    public function setSearch(string $term = ''): TableList 
    {
        $this->search = $term;
        return $this;
    }

    public function setSearchColumns(array $columns = []): TableList 
    {
        $this->searchColumns = $columns;
        return $this;
    }

    public function setFilters(array $filters = []): TableList 
    {
        $this->filters = $filters;
        return $this;
    }

    public function setJoins(array $joins = []): TableList 
    {
        $this->joins = $joins;
        return $this;
    }

    public function setClass(string $className): TableList  
    {
        $this->className = $className;
        return $this;
    }

    public function setStatement(string $statement): TableList
    {
        $this->statement = $statement;
        return $this;
    }

    public function find() 
    {
        if($this->statement) {
            $model = $this->className::getDataLayerByRawSQL(
                $this->statement, $this->limit, $this->page, [$this->orderKey => $this->orderType]
            );
        } else {
            $model = $this->className::getDataLayer(
                [
                    'search' => [
                        'term' => $this->search, 
                        'columns' => $this->searchColumns
                    ]
                ] + $this->filters, 
                $this->joins, 
                $this->columns, 
                $this->limit, 
                $this->page, 
                [$this->orderKey => $this->orderType]
            );
        }

        return $this->fetch($model);
    }

    private function fetch($model) 
    {
        try {
            $results = $model->fetch(true);
            if($model && $model->fail()) {
                throw new LogException(
                    $model->fail()->getMessage(), 
                    'Lamentamos, mas ocorreu um erro inesperado no momento de trazer os dados da tabela! 
                    Contate o administrador para que isso seja resolvido.');
            }
            
            $this->results = $results;

            $count = 0;

            if($this->statement) {
                $dataLayer = $this->className::getDataLayerByRawSQL($this->statement);
                $count = $dataLayer->count();
            } else {
                $count = $this->className::getCount([
                    'search' => [
                        'term' => $this->search, 
                        'columns' => $this->searchColumns
                    ]
                ] + $this->filters, $this->joins);
            }

            $this->lastPage = ceil($count/$this->limit);
            return $this;
        } catch(Exception $exception) {
            $this->error = $exception;
            return false;
        }
    }

    public function getLastPage() 
    {
        return $this->lastPage;
    }

    public function getResults() 
    {
        return $this->results;
    }

    public function getDataTable(
        array $rows,
        array $headers = [], 
        array $filters = [], 
        array $selected = []
    ): DataTable 
    {
        $dataTable = new DataTable($this->page, $this->orderKey, $this->orderType, $this->limit);
        return $dataTable->search($this->search)
            ->headers($headers)
            ->selectedOptions($selected)
            ->filters($filters)
            ->lastPage($this->lastPage)
            ->content($rows);
    }

    public function error(): ?Exception 
    {
        return $this->error;
    }
}