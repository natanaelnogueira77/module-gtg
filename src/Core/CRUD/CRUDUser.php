<?php

namespace Src\Core\CRUD;

use Exception;
use Src\Core\Models\User;

class CRUDUser
{
    private $error;

    public function get(int $userId): ?User 
    {
        $dbUser = User::getById($userId);
        if(!$dbUser) {
            throw new Exception('O usuário não foi encontrado!');
        }

        return $dbUser;
    }

    public function create(array $data = []): ?User 
    {   
        $dbUser = new User();
        $dbUser->setValues([
            'utip_id' => $data['utip_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'slug' => $data['slug']
        ]);
        $dbUser->save();

        return $dbUser;
    }

    public function update(int $userId, array $data = []): ?User 
    {
        $dbUser = User::getById($userId);
        if(!$dbUser) {
            throw new Exception('O usuário não foi encontrado!');
        }

        $dbUser->setValues([
            'utip_id' => $data['utip_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => (
                $data['update_password'] 
                ? $data['password'] 
                : $dbUser->password
            ),
            'slug' => $data['slug']
        ]);
        $dbUser->save();

        return $dbUser;
    }

    public function list(array $filters = []): void 
    {
        $dbUsers = User::get($filters);
    }

    public function delete(int $userId): ?User 
    {
        $dbUser = User::getById($userId);
        if(!$dbUser) {
            throw new Exception('Nenhum usuário foi encontrado!');
        }

        $dbUser->destroy();
        return $dbUser;
    }

    public function error(): ?Exception 
    {
        return $this->error;
    }
}