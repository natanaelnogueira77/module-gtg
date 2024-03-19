<?php 

namespace Src\Views\Pages;

class UsersPage 
{
    public function __construct(
        private array $userTypes
    ) 
    {}

    public function getUserTypes(): array 
    {
        return $this->userTypes;
    }
}