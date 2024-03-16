<?php 

namespace Src\Views\Widgets\Forms;

use Src\Models\AR\User;

class SaveUser 
{
    public function __construct(
        private string $id,
        private array $userTypes,
        private ?string $action = null,
        private ?string $method = null,
        private ?User $user = null,
        private bool $isAccountEdit = false
    ) 
    {}

    public function getId(): string 
    {
        return $this->id;
    }

    public function getUserTypes(): array 
    {
        return $this->userTypes;
    }

    public function getAction(): string 
    {
        return $this->action ?? '';
    }

    public function getMethod(): string 
    {
        return $this->method ?? '';
    }

    public function getUser(): ?User 
    {
        return $this->user;
    }

    public function isAccountEdit(): bool 
    {
        return $this->isAccountEdit;
    }
}