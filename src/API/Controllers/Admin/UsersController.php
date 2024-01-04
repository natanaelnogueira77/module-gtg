<?php

namespace Src\API\Controllers\Admin;

use GTG\MVC\Components\Email;
use GTG\MVC\Controller;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\UserForm;
use Src\Models\UserType;
use Src\Utils\ErrorMessages;

class UsersController extends Controller 
{
    private ?User $user = null;

    private function user(int $userId): ?User
    {
        if(!$this->user = (new User())->findById($userId)) {
            $this->setMessage('error', _('Nenhum usuário foi encontrado!'))->APIResponse([], 404);
            return null;
        }

        return $this->user;
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

        $this->user = new User();
        if(!$this->user->loadData($data)->save()) {
            $this->setMessage('error', ErrorMessages::form())->setErrors(
                $this->user->getFirstErrors()
            )->APIResponse([], 422);
            return;
        }

        $email = new Email();
        $email->add(
            _('Você foi registrado com sucesso!'), 
            $this->getView('emails/user-register', [
                'user' => $this->user,
                'password' => $data['password'],
                'logo' => Config::getLogoURL()
            ]), 
            $this->user->name, 
            $this->user->email
        );

        if(!$email->send()) {
            $this->session->setFlash(
                'success', 
                sprintf(
                    _('O usuário "%s" foi cadastrado com sucesso! Porém não foi possível enviar uma notificação no email dele.'), 
                    $this->user->name
                )
            );
        } else {
            $this->session->setFlash(
                'success', 
                sprintf(
                    _('O usuário "%s" foi criado com sucesso!'), 
                    $this->user->name
                )
            );
        }

        $this->APIResponse([
            'link' => $this->getRoute('admin.users.edit', ['user_id' => $this->user->id])
        ], 200);
    }

    public function update(array $data): void 
    {
        if(!$this->user(intval($data['user_id']))) return;

        $userForm = (new UserForm())->loadData([
            'id' => $this->user->id,
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

        $this->user->loadData([
            'utip_id' => intval($data['utip_id']),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['update_password'] ? $data['password'] : $this->user->password,
            'slug' => $data['slug']
        ]);

        if(!$this->user->save()) {
            $this->setMessage('error', ErrorMessages::form())->setErrors(
                $this->user->getFirstErrors()
            )->APIResponse([], 422);
            return;
        }

        $this->setMessage(
            'success', 
            sprintf(
                _('Os dados do usuário "%s" foram alterados com sucesso!'), 
                $this->user->name
            )
        )->APIResponse([], 200);
    }

    public function list(array $data): void 
    {
        $data = array_merge($data, filter_input_array(INPUT_GET, FILTER_DEFAULT) ?? []);

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
        if(!$this->user(intval($data['user_id']))) return;
        if(!$this->user->destroy()) {
            $this->setMessage('error', _('Não foi possível excluir o usuário!'))->APIResponse([], 422);
            return;
        }

        $this->setMessage(
            'success', 
            sprintf(
                _('O usuário "%s" foi excluído com sucesso.'), 
                $this->user->name
            )
        )->APIResponse([], 200);
    }
}