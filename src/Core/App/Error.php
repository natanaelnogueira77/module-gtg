<?php

namespace Src\Core\App;

use League\Plates\Engine;

class Error 
{
    protected $view;

    public function __construct() 
    {
        $this->view = new Engine(__DIR__ . '/../../../themes/error', 'php');
    }

    public function error(array $data): void 
    {
        $message = '';
        if($data['errcode'] == 400) {
            $message = 'Lamentamos, mas houve algum erro na requisição deste URL!';
        } elseif($data['errcode'] == 403) {
            $message = 'Parece que o servidor recusou essa ação!';
        } elseif($data['errcode'] == 404) {
            $message = 'A página que você procurou não foi encontrada!';
        } elseif($data['errcode'] == 405) {
            $message = 'A requisição para essa página não foi bem sucedida!';
        } elseif($data['errcode'] == 500) {
            $message = 'Oops! Parece que ocorreu um erro no servidor.';
        }

        echo $this->view->render('error', [
            'errcode' => $data['errcode'],
            'errmessage' => $message
        ]);
    }
}