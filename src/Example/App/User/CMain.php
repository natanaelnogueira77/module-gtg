<?php

namespace Src\Example\App\User;

use Src\Example\App\User\CTemplate;
use Src\Core\Models\UserMeta;

class CMain extends CTemplate 
{
    public function main(array $data): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $exception = null;

        $blocks = [
            [
                'title' => 'Example',
                'url' => url('u'),
                'text' => 'Example'
            ]
        ];

        $this->loadView('page', [
            'title' => 'Painel do Usuário | ' . SITE,
            'page' => [
                'title' => 'Página do Usuário',
                'subtitle' => 'Informações sobre sua atividade no sistema',
                'icon' => 'pe-7s-user',
                'icon_color' => 'bg-malibu-beach'
            ],
            'template' => $this->getTemplateView('main', [
                'blocks' => $blocks
            ]),
            'scripts' => [$this->getScript('main')],
            'exception' => $exception
        ]);
    }

    public function saveMeta(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            UserMeta::saveMetas($user->id, $data);
            $this->setMessage('Configurações atualizadas com sucesso!');
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }
}