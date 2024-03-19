<?php 

namespace Src\Views\Pages;

use Src\Models\AR\User;

class EditAccountPage 
{
    public function __construct(
        private User $user,
        private array $userTypes
    ) 
    {}

    public function getUser(): User 
    {
        return $this->user;
    }

    public function getUserTypes(): array 
    {
        return $this->userTypes;
    }
}