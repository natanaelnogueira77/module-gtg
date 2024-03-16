<?php 

namespace Src\Views\Widgets\Sections;

use Src\Models\AR\User;

class EditAccount 
{
    public function __construct(
        private string $formId,
        private User $user,
        private array $userTypes
    ) 
    {}

    public function getFormId(): string 
    {
        return $this->formId;
    }

    public function getUser(): User 
    {
        return $this->user;
    }

    public function getUserTypes(): array 
    {
        return $this->userTypes;
    }
}