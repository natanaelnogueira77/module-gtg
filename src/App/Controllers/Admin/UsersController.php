<?php

namespace Src\App\Controllers\Admin;

use Src\App\Controllers\Admin\TemplateController;
use Src\Models\User;
use Src\Models\UserType;
use Src\Utils\ErrorMessages;

class UsersController extends TemplateController 
{
    public function index(array $data): void 
    {
        $this->addData();
        $this->render('admin/users/index', [
            'userTypes' => (new UserType())->get()->fetch(true)
        ]);
    }

    public function create(array $data): void 
    {
        $this->addData();
        $this->render('admin/users/save', [
            'userTypes' => (new UserType())->get()->fetch(true)
        ]);
    }

    public function edit(array $data): void 
    {
        $this->addData();

        if(!$dbUser = (new User())->findById(intval($data['user_id']))) {
            $this->session->setFlash('error', _('Nenhum usuário foi encontrado!'));
            $this->redirect('admin.users.index');
        }

        $this->render('admin/users/save', [
            'dbUser' => $dbUser,
            'userTypes' => (new UserType())->get()->fetch(true)
        ]);
    }
}