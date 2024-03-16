<?php 

namespace GTG\MVC\DTOs;

final class ErrorDTO 
{
    public function __construct(
        private readonly int $type,
        private readonly string $message, 
        private readonly ?string $file = null, 
        private readonly ?int $line = null
    ) {}

    public function getType(): int 
    {
        return $this->type;
    }

    public function getMessage(): string 
    {
        return $this->message;
    }

    public function getFile(): ?string 
    {
        return $this->file;
    }

    public function getLine(): ?int 
    {
        return $this->line;
    }
}