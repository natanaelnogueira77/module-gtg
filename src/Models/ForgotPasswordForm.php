<?php

namespace Src\Models;

use Src\Exceptions\ValidationException;
use Src\Models\AR\{ User, UserMeta };

class ForgotPasswordForm extends Model 
{
    public function __construct(
        public ?string $email = null
    ) 
    {}

    public function rules(): array 
    {
        return [
            $this->createRule()->required('email')->setMessage(_('O email é obrigatório!')),
            $this->createRule()->email('email')->setMessage(_('O email é invalido!')),
            $this->createRule()->maxLength('email', 100)->setMessage(sprintf(_('O email deve ter %s caractéres ou menos!'), 100))
        ];
    }

    public function getUser(): ?User 
    {
        $this->validate();

        if(!$user = User::getByEmail($this->email)) {
            throw new ValidationException([
                'email' => _('Este email não foi encontrado!'),
                _('Este email não foi encontrado!')
            ]);
        } elseif($lastRequest = $user->getLastResetPasswordRequest()) {
            if(strtotime($lastRequest) >= strtotime('-1 hour')) {
                throw new ValidationException([
                    'email' => _('Uma solicitação já foi enviada para este email! Espere 1 hora para enviar outra.'),
                    _('Uma solicitação já foi enviada para este email! Espere 1 hora para enviar outra.')
                ]);
            }
        }

        return $user;
    }
}