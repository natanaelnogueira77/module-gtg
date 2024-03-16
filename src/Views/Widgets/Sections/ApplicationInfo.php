<?php 

namespace Src\Views\Widgets\Sections;

class ApplicationInfo 
{
    public function __construct(
        private string $applicationName,
        private string $applicationVersion,
        private array $userTypes,
        private array $usersCount
    ) 
    {}

    public function getApplicationName(): string 
    {
        return $this->applicationName;
    }

    public function getApplicationVersion(): string 
    {
        return $this->applicationVersion;
    }

    public function getUserTypes(): array 
    {
        return $this->userTypes;
    }

    public function getUsersCount(): array 
    {
        return $this->usersCount;
    }
}