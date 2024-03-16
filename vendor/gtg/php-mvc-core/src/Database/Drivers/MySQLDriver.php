<?php

namespace GTG\MVC\Database\Drivers;

use GTG\MVC\Database\Drivers\Driver;

class MySQLDriver implements Driver 
{
    public function __construct(
        private readonly string $name,
        private readonly string $host,
        private readonly string $port,
        private readonly string $username,
        private readonly string $password,
        private readonly ?array $options = null
    ) 
    {}

    public function getConnectionData(): array 
    {
        return [
            'driver' => 'mysql',
            'dbname' => $this->name,
            'host' => $this->host,
            'port' => $this->port,
            'username' => $this->username,
            'passwd' => $this->password,
            'options' => $this->options
        ];
    }

    public function getMigrationsTableCreationStatement(): string 
    {
        return '
            CREATE TABLE IF NOT EXISTS migrations (
                id INT(1) AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
        ';
    }

    public function getMigrationsSelectStatement(): string 
    {
        return 'SELECT id, migration FROM migrations';
    }

    public function getDescribeQuery(string $entity): string 
    {
        return 'DESCRIBE ' . $entity;
    }

    public function getFiltersForStatement(array $filters = []): array 
    {
        return [
            $this->getTermsForDataLayer($filters), 
            $this->getParamsForDataLayer($filters)
        ];
    }

    private function getTermsForDataLayer(array $filters = []): string 
    {
        $terms = '';
        $count = 0;
        
        if(count($filters) > 0) {
            foreach($filters as $column => $value) {
                $count++;
                if($column == 'raw') {
                    $terms .= "{$value} AND ";
                } elseif($column == 'search') {
                    if($value['term'] && $value['columns']) {
                        $terms .= static::getSearchFilterStatement($value['term'], $value['columns']) . ' AND ';
                    }
                } elseif(in_array($column, ['>=', '<=', '<', '>', '!=', '<>'])) {
                    if($value) {
                        foreach($value as $col => $val) {
                            $terms .= "{$col} {$column} " . static::getFormatedValue($val) . ' AND ';
                        }
                    }
                } elseif($column == 'is_null' || $column == 'is_not_null') {
                    if($value) {
                        foreach($value as $col) {
                            if($column == 'is_null') {
                                $terms .= "{$col} IS NULL AND ";
                            } elseif($column == 'is_not_null') {
                                $terms .= "{$col} IS NOT NULL AND ";
                            }
                        }
                    }
                } elseif($column == 'between') {
                    if($value) {
                        foreach($value as $col => $val) {
                            if(is_array($val)) {
                                $terms .= "{$col} BETWEEN " . static::getFormatedValue($val[0]) 
                                    . " AND " . static::getFormatedValue($val[1]) . " AND ";
                            }
                        }
                    }
                } elseif($column == 'in') {
                    if($value) {
                        foreach($value as $col => $values) {
                            $in = implode(',', array_map(function ($e) { return static::getFormatedValue($e); }, $values));
                            $terms .= "{$col} IN ({$in}) AND ";
                        }
                    }
                } else {
                    $terms .= "{$column} = :param{$count} AND ";
                }
            }

            if($terms) $terms = substr($terms, 0, -4);
        }

        return $terms;
    }

    private static function getSearchFilterStatement(string $terms = '', array $attributes = []): string 
    {
        $query = '';
        if($terms && $attributes) {
            $words = explode(' ', $terms);
            $conds = array();
            $searches = array();
            $numCols = count($attributes);
    
            foreach($words as $word) {
                $col = 1;
                foreach($attributes as $attr) {
                    $open = $col == 1 ? ' ( ' : '';
                    $close = $col == $numCols ? ' ) ' : '';
                    $conds[] = "{$open} {$attr} LIKE '%" . $word . "%' {$close}";
                    $col++;
                }
                $searches[] = implode(' OR ', $conds);
                $conds = [];
                $col = 1;
            }
    
            $query .= implode(' AND ', $searches);
        }
        return $query;
    }

    private static function getFormatedValue(mixed $value): mixed 
    {
        if(is_null($value)) {
            return 'null';
        } elseif(gettype($value) === 'string') {
            $value = html_entity_decode($value);
            return "'${value}'";
        } else {
            return $value;
        }
    }

    private function getParamsForDataLayer(array $filters = []): string 
    {
        $params = '';
        $count = 0;

        if(count($filters) > 0) {
            foreach($filters as $column => $value) {
                $count++;
                if($column == 'raw') {
                } elseif($column == 'search') {
                } elseif(in_array($column, ['>=', '<=', '<', '>', '!=', '<>'])) {
                } elseif($column == 'is_null' || $column == 'is_not_null') {
                } elseif($column == 'between') {
                } elseif($column == 'in') {
                } else {
                    $params .= "param{$count}={$value}&";
                }
            }

            if($params) $params = substr($params, 0, -1);
        }

        return $params;
    }
}