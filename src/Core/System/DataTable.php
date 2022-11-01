<?php

namespace Src\Core\System;

class DataTable 
{
    private $page;
    private $order_key;
    private $order_type;
    private $limit;
    private $search;
    private $no_lines;
    private $no_search;
    private $no_buttons;
    private $filters;
    private $headers;
    private $data;
    private $no_data_msg;
    private $last_page;
    private $filters_selected;

    public function __construct(
        int $page = 1, 
        string $order_key = '', 
        string $order_type = 'ASC', 
        int $limit = 10
    )
    {
        $this->page = $page;
        $this->order_key = $order_key;
        $this->order_type = $order_type;
        $this->limit = $limit;
    }

    public function page(int $page): DataTable
    {
        $this->page = $page;
        return $this;
    }

    public function order(string $key, string $order = 'ASC'): DataTable 
    {
        $this->order_key = $key;
        $this->order_type = $order;
        return $this;
    }

    public function limit(int $limit = 10): DataTable 
    {
        $this->limit = $limit;
        return $this;
    }

    public function headers(array $headers = []): DataTable 
    {
        $newHeaders = [];
        if($headers) {
            foreach($headers as $header) {
                $array = [
                    'name' => isset($header[0]) ? $header[0] : '', 
                    'key' => isset($header[1]) ? $header[1] : false, 
                    'sort' => isset($header[2]) ? $header[2] : false
                ];
                if($header[1] == $this->order_key) {
                    $array['selected'] = true;
                    if($this->order_type == 'ASC') {
                        $array['order'] = 'DESC';
                    } elseif($this->order_type == 'DESC') {
                        $array['order'] = 'ASC';
                    }
                } else {
                    $array['selected'] = false;
                    $array['order'] = 'ASC';
                }

                

                $newHeaders[] = $array;
            }
        }

        $this->headers = $newHeaders;
        return $this;
    }

    public function selectedOptions(array $selected = []): DataTable 
    {
        if($selected) {
            foreach($selected as $key => $value) {
                $this->filters_selected[$key] = $value;
            }
        }

        return $this;
    }

    public function filters(array $filters = []): DataTable 
    {
        $newFilters = [];

        if($filters) {
            foreach($filters as $filter) {
                if($filter[2] == 'select') {
                    $array = [
                        'key' => $filter[0],
                        'type' => $filter[2],
                        'label' => $filter[3]
                    ];

                    foreach($filter[1] as $option) {
                        $values = [
                            'text' => $option[0],
                            'value' => $option[1]
                        ];
    
                        if($option[1] == $this->filters_selected[$filter[0]]) {
                            $values['selected'] = true;
                        } else {
                            $values['selected'] = false;
                        }
                        
                        $array['options'][] = $values;
                    }

                    $newFilters[] = $array;
                } elseif(in_array($filter[2], ['text', 'date', 'time', 'number', 'email'])) {
                    $array = [
                        'type' => $filter[2],
                        'key' => $filter[0],
                        'value' => $this->filters_selected[$filter[0]],
                        'label' => $filter[3]
                    ];
                    $newFilters[] = $array;
                } elseif(in_array($filter[2], ['multiple'])) {
                    $array = [
                        'key' => $filter[0],
                        'type' => $filter[2],
                        'label' => $filter[3]
                    ];

                    foreach($filter[1] as $option) {
                        $values = [
                            'text' => $option[0],
                            'value' => $option[1]
                        ];
    
                        if(in_array($option[1], $this->filters_selected[$filter[0]])) {
                            $values['selected'] = true;
                        } else {
                            $values['selected'] = false;
                        }
                        
                        $array['options'][] = $values;
                    }

                    $newFilters[] = $array;
                }
            }
        }

        $this->filters = $newFilters;
        return $this;
    }

    public function lastPage(int $page): DataTable 
    {
        $this->last_page = $page;
        return $this;
    }

    public function search(string $term = ''): DataTable 
    {
        $this->search = $term;
        return $this;
    }

    public function content(array $data = []): DataTable 
    {
        $this->data = $data;
        return $this;
    }

    public function noLines(bool $no_lines = true): DataTable 
    {
        $this->no_lines = $no_lines;
        return $this;
    }


    public function noSearch(bool $no_search = true): DataTable 
    {
        $this->no_search = $no_search;
        return $this;
    }

    public function noButtons(bool $no_buttons = true): DataTable 
    {
        $this->no_buttons = $no_buttons;
        return $this;
    }

    public function noDataMsg(string $no_data_msg = ''): DataTable 
    {
        $this->no_data_msg = $no_data_msg;
        return $this;
    }

    public function get(): array 
    {
        $table = [
            'filters' => $this->filters,
            'limit' => $this->limit,
            'search' => $this->search,
            'no_lines' => $this->no_lines,
            'no_search' => $this->no_search,
            'no_buttons' => $this->no_buttons,
            'no_data_msg' => $this->no_data_msg,
            'last_page' => $this->last_page,
            'page' => $this->page,
            'headers' => $this->headers,
            'data' => $this->data
        ];

        return $table;
    }
}