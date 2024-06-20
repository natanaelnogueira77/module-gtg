<?php 

namespace Views\Components\Emails;

use Models\AR\User;

final class RegisteredUserEmail extends Email
{
    public function __construct(
        public readonly User $user, 
        public readonly string $password
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/emails/registered-user', ['view' => $this]);
    }
}