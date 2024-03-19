<?php 

namespace Src\Views\Pages;

class AdminPage 
{
    public function __construct(
        private array $userTypes,
        private array $usersCount,
        private ?array $configValues = null
    ) 
    {}

    public function getUserTypes(): array 
    {
        return $this->userTypes;
    }

    public function getUsersCount(): array 
    {
        return $this->usersCount;
    }

    public function getConfigValues(): ?array 
    {
        return $this->configValues;
    }
}