<?php

namespace Src\Example\App\User;

use Src\Example\App\User\CTemplate;
use Src\Core\Models\User;

class CEditAccount extends CTemplate 
{
    public function main(array $data): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $exception = null;

        $userData = [];

        $urls = [
            'save' => $this->getRoute('CEAUEditAccount.save'),
            'validate_slug' => $this->getRoute('CEAWMain.validateSlug'),
            'return' => url('u')
        ];

        if($user) $userData = $user->getValues();

        $this->loadView('page', [
            'title' => 'Editar Conta | ' . SITE,
            'page' => [
                'title' => 'Editar Conta',
                'subtitle' => 'Edite os Detalhes de sua Conta logo abaixo',
                'icon' => 'pe-7s-user',
                'icon_color' => 'bg-malibu-beach'
            ],
            'template' => $this->getTemplateView('edit-account', $userData + [
                'urls' => $urls
            ]),
            'scripts' => [$this->getScript('edit-account')],
            'exception' => $exception
        ]);
    }

    public function save(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];
        
        try {
            $data['password'] = $data['update_password'] ? $data['password'] : $user->password;

            $dbUser = new User();
            $dbUser->setValues([
                'id' => $user->id,
                'utip_id' => $user->utip_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'slug' => $data['slug'],
                'date_c' => $user->date_c
            ]);
            $dbUser->save();

            $this->setSessionUser($dbUser);
            $this->setMessage('Seus dados foram alterados com sucesso!');
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }
}