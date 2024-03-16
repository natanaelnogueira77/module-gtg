<?php 

namespace GTG\MVC\Database\Drivers;

interface Driver 
{
    public function getConnectionData(): array;
    public function getMigrationsTableCreationStatement(): string;
    public function getMigrationsSelectStatement(): string;
    public function getDescribeQuery(string $entity): string;
}