<?php

namespace Src\Models;

use DateTime;
use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\Model;
use Src\Models\User;

class SocialUser extends Model 
{
    protected static $tableName = 'social_usuario';
    protected static $hasTimestamps = true;
    protected static $columns = [
        'usu_id',
        'social_id',
        'email',
        'social'
    ];
    protected static $required = [
        'usu_id',
        'social_id',
        'email',
        'social'
    ];

    public function save(): bool 
    {
        $this->validate();
        return parent::save();
    }

    public function getUser(): ?User
    {
        $this->user = User::getById($this->usu_id);
    }

    public static function getBySocialId(string $socialId, string $social): ?self 
    {
        $object = (new self())->get([
            'social_id' => $socialId,
            'social' => $social
        ])->fetch(false);

        return $object;
    }

    public static function getBySocialEmail(string $email, string $social): ?self 
    {
        $object = (new self())->get([
            'email' => $email,
            'social' => $social
        ])->fetch(false);

        return $object;
    }

    private function validate(): void 
    {
        $errors = [];

        $lang = getLang()->setFilepath('models/social-user')->getContent()->setBase('validate');
        
        if(!$this->usu_id) {
            $errors['usu_id'] = $lang->get('user.required');
        }

        if(!$this->social_id) {
            $errors['social_id'] = $lang->get('social_id.required');
        }

        if(!$this->social) {
            $errors['social'] = $lang->get('social.required');
        } elseif(!in_array($this->social, ['facebook', 'google'])) {
            $errors['social'] = $lang->get('social.invalid');
        }

        if(!$this->email) {
            $errors['email'] = $lang->get('email.required');
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = $lang->get('email.invalid');
        } elseif(strlen($this->email) > 100) {
            $errors['email'] = $lang->get('email.max');
        } else {
            if(!$this->id) {
                $email = (new self())
                    ->find('email = :email', "email={$this->email}")
                    ->count();
            } else {
                $email = (new self())
                    ->find('email = :email AND id != :id', "email={$this->email}&id={$this->id}")
                    ->count();
            }

            if($email) {
                $errors['email'] = $lang->get('email.exists');
            }
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, $lang->get('error_message'));
        }
    }
}