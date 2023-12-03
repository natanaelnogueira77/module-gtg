<?php

namespace Src\App\Controllers\Admin;

use GTG\MVC\Components\Email;
use Src\App\Controllers\Admin\TemplateController;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\UserForm;
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

    public function store(array $data): void 
    {
        $userForm = (new UserForm())->loadData([
            'utip_id' => intval($data['utip_id']),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'password_confirm' => $data['password_confirm']
        ]);
        if(!$userForm->validate()) {
            $this->setMessage('error', ErrorMessages::form())->setErrors(
                $userForm->getFirstErrors()
            )->APIResponse([], 422);
            return;
        }

        $dbUser = new User();
        if(!$dbUser->loadData($data)->save()) {
            $this->setMessage('error', ErrorMessages::form())->setErrors(
                $dbUser->getFirstErrors()
            )->APIResponse([], 422);
            return;
        }

        $email = new Email();
        $email->add(
            _('Você foi registrado com sucesso!'), 
            $this->getView('emails/user-register', [
                'user' => $dbUser,
                'password' => $data['password'],
                'logo' => url((new Config())->getMeta(Config::KEY_LOGO))
            ]), 
            $dbUser->name, 
            $dbUser->email
        );

        if(!$email->send()) {
            $this->session->setFlash(
                'success', 
                sprintf(
                    _('O usuário "%s" foi cadastrado com sucesso! Porém não foi possível enviar uma notificação no email dele.'), 
                    $dbUser->name
                )
            );
        } else {
            $this->session->setFlash(
                'success', 
                sprintf(
                    _('O usuário "%s" foi criado com sucesso!'), 
                    $dbUser->name
                )
            );
        }

        $this->APIResponse([
            'link' => $this->getRoute('admin.users.edit', ['user_id' => $dbUser->id])
        ], 200);
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

    public function update(array $data): void 
    {
        if(!$dbUser = (new User())->findById(intval($data['user_id']))) {
            $this->setMessage('error', _('Nenhum usuário foi encontrado!'))->APIResponse([], 422);
            return;
        }

        $userForm = (new UserForm())->loadData([
            'id' => $dbUser->id,
            'utip_id' => intval($data['utip_id']),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'password_confirm' => $data['password_confirm'],
            'update_password' => $data['update_password'] ? true : false
        ]);
        if(!$userForm->validate()) {
            $this->setMessage('error', ErrorMessages::form())->setErrors(
                $userForm->getFirstErrors()
            )->APIResponse([], 422);
            return;
        }

        $dbUser->loadData([
            'utip_id' => intval($data['utip_id']),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['update_password'] ? $data['password'] : $dbUser->password,
            'slug' => $data['slug']
        ]);

        if(!$dbUser->save()) {
            $this->setMessage('error', ErrorMessages::form())->setErrors(
                $dbUser->getFirstErrors()
            )->APIResponse([], 422);
            return;
        }

        $this->setMessage(
            'success', 
            sprintf(
                _('Os dados do usuário "%s" foram alterados com sucesso!'), 
                $dbUser->name
            )
        )->APIResponse([], 200);
    }

    public function list(array $data): void 
    {
        $data = array_merge($data, filter_input_array(INPUT_GET, FILTER_DEFAULT));

        $filters = [];

        $limit = $data['limit'] ? intval($data['limit']) : 10;
        $page = $data['page'] ? intval($data['page']) : 1;
        $order = $data['order'] ? $data['order'] : 'id';
        $orderType = $data['orderType'] ? $data['orderType'] : 'ASC';

        if($data['search']) {
            $filters['search'] = [
                'term' => $data['search'],
                'columns' => ['name']
            ];
        }

        if($data['user_type']) {
            $filters['utip_id'] = $data['user_type'];
        }

        $users = (new User())->get($filters)->paginate($limit, $page)->sort([$order => $orderType]);
        $count = $users->count();
        $pages = ceil($count / $limit);
        
        if($objects = $users->fetch(true)) {
            $objects = User::withUserType($objects);
        }

        $this->APIResponse([
            'content' => [
                'table' => $this->getView('admin/users/_partials/data-table', [
                    'data' => $objects,
                    'order' => $order,
                    'orderType' => $orderType
                ]),
                'pagination' => $this->getView('_components/pagination', [
                    'pages' => $pages,
                    'currPage' => $page,
                    'results' => $count,
                    'limit' => $limit
                ])
            ]
        ], 200);
    }

    public function delete(array $data): void 
    {
        if(!$dbUser = (new User())->findById(intval($data['user_id']))) {
            $this->setMessage('error', _('Nenhum usuário foi encontrado!'))->APIResponse([], 404);
            return;
        } elseif(!$dbUser->destroy()) {
            $this->setMessage('error', _('Não foi possível excluir o usuário!'))->APIResponse([], 422);
            return;
        }

        $this->setMessage(
            'success', 
            sprintf(
                _('O usuário "%s" foi excluído com sucesso.'), 
                $dbUser->name
            )
        )->APIResponse([], 200);
    }
}