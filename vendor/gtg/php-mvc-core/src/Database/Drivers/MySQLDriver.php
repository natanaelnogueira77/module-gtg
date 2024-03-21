<?php

namespace GTG\MVC\Database\Drivers;

final class MySQLDriver implements Driver 
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
}