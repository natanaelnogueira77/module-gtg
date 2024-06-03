<?php

namespace Src\Models\AR;

use DateTime;

class Notification extends ActiveRecord 
{
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
    
    public static function hasTimestamps(): bool 
    {
        return true;
    }

    public function rules(): array 
    {
        return [
            $this->createRule()->required('usu_id')->setMessage(_('O usuário é obrigatório!')),
            $this->createRule()->required('content')->setMessage(_('O conteúdo é obrigatório!')),
            $this->createRule()->maxLength('content', 1000)->setMessage(sprintf(_('O conteúdo deve ter %s caractéres ou menos!'), 1000))
        ];
    }

    public static function getByUserId(int $userId, string $columns = '*'): ?array 
    {
        return self::get($columns)->filters(fn($where) => $where->equal('usu_id')->assignment($userId))->fetch(true);
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

    public static function getUnreadByUser(User $user, string $columns = '*'): ?array 
    {
        return self::get($columns)->filters(function($where) use ($user) {
            $where->equal('usu_id')->assignment($user->id);
            $where->equal('was_read')->assignment(0);
        })->order('created_at DESC')->fetch(true);
    }
}