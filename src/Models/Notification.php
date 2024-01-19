<?php

namespace Src\Models;

use DateTime;
use GTG\MVC\DB\DBModel;
use Src\Models\User;

class Notification extends DBModel 
{
    public ?User $user = null;

    public static function tableName(): string 
    {
        return 'notificacao';
    }

    public static function primaryKey(): string 
    {
        return 'id';
    }

    public static function attributes(): array 
    {
        return [
            'usu_id', 
            'content', 
            'was_read'
        ];
    }

    public function rules(): array 
    {
        return [
            'usu_id' => [
                [self::RULE_REQUIRED, 'message' => _('O usuário é obrigatório!')]
            ],
            'content' => [
                [self::RULE_REQUIRED, 'message' => _('O conteúdo é obrigatório!')],
                [self::RULE_MAX, 'max' => 1000, 'message' => sprintf(_('O conteúdo deve conter no máximo %s caractéres!'), 1000)]
            ]
        ];
    }

    public function encode(): static 
    {
        $this->was_read = $this->was_read ? 1 : 0;
        return $this;
    }

    public function user(string $columns = '*'): ?User
    {
        $this->user = $this->belongsTo(User::class, 'usu_id', 'id', $columns)->fetch(false);
        return $this->user;
    }

    public static function withUser(
        array $objects, 
        array $filters = [], 
        string $columns = '*', 
        ?callable $transformation = null
    ): array
    {
        return self::withBelongsTo(
            $objects, 
            User::class, 
            'usu_id', 
            'user', 
            'id', 
            $filters, 
            $columns,
            $transformation
        );
    }

    public static function getByUserId(int $userId, string $columns = '*'): ?array 
    {
        return (new self())->get(['usu_id' => $userId], $columns)->fetch(true);
    }

    public function getContent(): string 
    {
        return $this->content;
    }

    public function wasRead(): bool 
    {
        return $this->was_read ? true : false;
    }

    public function setAsRead(): self 
    {
        $this->was_read = 1;
        return $this;
    }

    public function setAsNotRead(): self 
    {
        $this->was_read = 0;
        return $this;
    }

    public function getCreatedAtDateTime(): DateTime 
    {
        return new DateTime($this->created_at);
    }

    public function getUpdatedAtDateTime(): DateTime 
    {
        return new DateTime($this->updated_at);
    }
}