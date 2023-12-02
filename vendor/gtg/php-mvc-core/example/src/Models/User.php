<?php

namespace GTG\MVC\Example\Src\Models;

use DateTime;
use GTG\MVC\DB\UserModel;

// DBModel works the same way as UserModel
class User extends UserModel 
{
    const UT_ADMIN = 1;
    const UT_STANDARD = 2;
    
    public static function tableName(): string 
    {
        return 'usuario';
    }

    public static function primaryKey(): string 
    {
        return 'id';
    }

    public static function attributes(): array 
    {
        return [
            'user_type', 
            'name', 
            'email', 
            'password', 
            'token', 
            'slug'
        ];
    }

    public function rules(): array 
    {
        return [
            'user_type' => [
                [self::RULE_REQUIRED, 'message' => 'The user type is required!']
            ],
            'name' => [
                [self::RULE_REQUIRED, 'message' => 'The name is required!'],
                [self::RULE_MAX, 'max' => 100, 'message' => sprintf('The name must have %s characters or less!', 100)]
            ],
            'email' => [
                [self::RULE_REQUIRED, 'message' => 'The email is required!'], 
                [self::RULE_EMAIL, 'message' => 'The name is invalid!'],
                [self::RULE_MAX, 'max' => 100, 'message' => sprintf('The email must have %s characters or less!', 100)]
            ],
            'password' => [
                [self::RULE_REQUIRED, 'message' => 'The password is required!'], 
                [self::RULE_MIN, 'min' => 5, 'message' => sprintf('The password must have %s characters or more!', 5)]
            ],
            'slug' => [
                [self::RULE_REQUIRED, 'message' => 'The nickname is required!'],
                [self::RULE_MAX, 'max' => 100, 'message' => sprintf('The nickname must have %s characters or less!', 100)]
            ],
            self::RULE_RAW => [
                function ($model) {
                    if(!$model->hasError('email')) {
                        if((new self())->get(['email' => $model->email] + (isset($model->id) ? ['!=' => ['id' => $model->id]] : []))->count()) {
                            $model->addError('email', _('The given email is already in use! Try another.'));
                        }
                    }
                    
                    if(!$model->hasError('slug')) {
                        if((new self())->get(['slug' => $model->slug] + (isset($model->id) ? ['!=' => ['id' => $model->id]] : []))->count()) {
                            $model->addError('slug', _('The given nickname is already in use! Try another.'));
                        }
                    }
                }
            ]
        ];
    }

    public function save(): bool 
    {
        $this->slug = is_string($this->slug) ? slugify($this->slug) : $this->getSlugByName();
        $this->email = strtolower($this->email);
        $this->token = is_string($this->email) ? md5($this->email) : null;

        return parent::save();
    }

    public function encode(): static 
    {
        if(!password_get_info($this->password)['algo']) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }

        return $this;
    }

    public function destroy(): bool 
    {
        if($this->isAdmin()) {
            $this->addError('destroy', 'You should not delete the system administrator!');
            return false;
        }
        return parent::destroy();
    }

    public function isAdmin(): bool
    {
        return $this->user_type == self::UT_ADMIN;
    }

    public static function getBySlug(string $slug, string $columns = '*'): ?self 
    {
        return (new self())->get(['slug' => $slug], $columns)->fetch(false);
    }

    public static function getByEmail(string $email, string $columns = '*'): ?self 
    {
        return (new self())->get(['email' => $email], $columns)->fetch(false);
    }

    public static function getByToken(string $token, string $columns = '*'): ?self 
    {
        return (new self())->get(['token' => $token], $columns)->fetch(false);
    }

    public function verifyPassword(string $password): bool 
    {
        return $this->password ? password_verify($password, $this->password) : false;
    }

    public function getSlugByName(): ?string 
    {
        return is_string($this->name) ? slugify($this->name . (new DateTime())->getTimestamp()) : null;
    }

    public function getCreatedAtDateTime(): DateTime 
    {
        return new DateTime($this->created_at);
    }

    public function getUpdatedAtDateTime(): DateTime 
    {
        return new DateTime($this->updated_at);
    }

    public function getAvatarURL(): string 
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
    }
}