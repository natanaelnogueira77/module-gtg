<?php

namespace Src\App\Controllers\Admin;

use ReflectionClass;
use Src\App\Controllers\Admin\Template;
use Src\Components\Email;
use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\UserType;

class CUsers extends Template 
{
    public function index(array $data): void 
    {
        $this->addData();
        $this->loadView('admin/users/index', [
            'userTypes' => (new UserType())->get()->fetch(true)
        ]);
    }

    public function create(array $data): void 
    {
        $this->addData();
        $this->loadView('admin/users/save', [
            'userTypes' => (new UserType())->get()->fetch(true)
        ]);
    }

    public function store(array $data): void 
    {
        $callback = [];
        
        try {
            $this->validateUser($data);

            $dbUser = new User();
            $dbUser->setValues([
                'utip_id' => $data['utip_id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'slug' => $data['slug']
            ])->save();

            $logo = (new Config())->getMeta('logo');
            $email = new Email();
            $email->add(
                _('Você se Registrou com Sucesso!'), 
                $this->getView('emails/user-register', [
                    'user' => $dbUser,
                    'password' => $data['password'],
                    'logo' => $logo ? url($logo) : ''
                ]), 
                $dbUser->name, 
                $dbUser->email
            );

            if(!$email->send()) {
                addSuccessMsg(
                    sprintf(
                        _('O usuário "%s" foi cadastrado com sucesso! Porém não foi possível enviar uma notificação no email dele.'), 
                        $dbUser->name
                    )
                );
            } else {
                addSuccessMsg(sprintf(_('O usuário "%s" foi criado com sucesso!'), $dbUser->name));
            }

            $callback['link'] = $this->getRoute('admin.users.edit', ['user_id' => $dbUser->id]);
            $callback['success'] = true;
        } catch(AppException $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function edit(array $data): void 
    {
        $this->addData();

        $dbUser = (new User())->findById(intval($data['user_id']));
        if(!$dbUser) {
            addErrorMsg(_('Nenhum usuário foi encontrado!'));
            $this->redirect('admin.users.index');
        }

        $this->loadView('admin/users/save', [
            'dbUser' => $dbUser,
            'userTypes' => (new UserType())->get()->fetch(true)
        ]);
    }

    public function update(array $data): void 
    {
        $callback = [];
        
        try {
            $dbUser = (new User())->findById(intval($data['user_id']));
            if(!$dbUser) {
                $this->throwException(_('Nenhum usuário foi encontrado!'));
            }

            $this->validateUser($data + ['id' => $dbUser->id]);
            $dbUser->setValues([
                'utip_id' => $data['utip_id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['update_password'] ? $data['password'] : $dbUser->password,
                'slug' => $data['slug']
            ])->save();

            $this->setMessage(sprintf(_('Os dados do usuário "%s" foram alterados com sucesso!'), $dbUser->name));
            $callback['success'] = true;
        } catch(AppException $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function list(array $data): void 
    {
        $data = array_merge($data, filter_input_array(INPUT_GET, FILTER_DEFAULT));
        $callback = [];

        try {
            $content = [];
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
            
            $objects = $users->fetch(true);
            if($objects) {
                User::withUserType($objects);
                foreach($objects as $user) {
                    $params = ['user_id' => $user->id];
                    $content[] = [
                        'id' => '#' . $user->id,
                        'name' => "
                            <div class=\"widget-content p-0\">
                                <div class=\"widget-content-wrapper\">
                                    <div class=\"widget-content-left mr-3\">
                                        <div class=\"widget-content-left\">
                                            <img width=\"40\" class=\"rounded-circle\" 
                                                src=\"https://www.gravatar.com/avatar/"
                                                . md5(strtolower(trim($user->email))) . "\">
                                        </div>
                                    </div>
                                    <div class=\"widget-content-left\">
                                        <div class=\"widget-heading\">{$user->name}</div>
                                        <div class=\"widget-subheading opacity-7\">
                                            {$user->userType->name_sing}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ",
                        'email' => $user->email,
                        'created_at' => $this->getDateTime($user->created_at)->format('d/m/Y'),
                        'actions' => "
                            <div class=\"dropup d-inline-block\">
                                <button type=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\" 
                                    data-toggle=\"dropdown\" class=\"dropdown-toggle btn btn-sm btn-primary\">
                                    " . _('Ações') . "
                                </button>
                                <div tabindex=\"-1\" role=\"menu\" aria-hidden=\"true\" class=\"dropdown-menu\">
                                    <h6 tabindex=\"-1\" class=\"dropdown-header\">" . _('Ações') . "</h6>
                                    <a href=\"{$this->getRoute('admin.users.edit', $params)}\" 
                                        type=\"button\" tabindex=\"0\" class=\"dropdown-item\">
                                        " . _('Editar Usuário') . "
                                    </a>

                                    <button type=\"button\" tabindex=\"0\" class=\"dropdown-item\" 
                                        data-act=\"delete\" data-method=\"delete\" 
                                        data-action=\"{$this->getRoute('admin.users.delete', $params)}\">
                                        " . _('Excluir Usuário') . "
                                    </button>
                                </div>
                            </div>
                        "
                    ];
                }
            }

            $callback['content'] = [
                'table' => $this->getView('components/data-table', [
                    'headers' => [
                        'actions' => ['text' => _('Ações')],
                        'id' => ['text' => _('ID'), 'sort' => true],
                        'name' => ['text' => _('Nome'), 'sort' => true],
                        'email' => ['text' => _('Email'), 'sort' => true],
                        'created_at' => ['text' => _('Criado em'), 'sort' => true]
                    ],
                    'order' => [
                        'selected' => $order,
                        'type' => $orderType
                    ],
                    'data' => $content
                ]),
                'pagination' => $pages > 1 
                    ? $this->getView('components/pagination', [
                        'pages' => $pages,
                        'currPage' => $page
                    ]) 
                    : null
            ];
            $callback['success'] = true;
        } catch(AppException $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }

    public function delete(array $data): void 
    {
        $callback = [];

        try {
            $dbUser = (new User())->findById(intval($data['user_id']));
            if(!$dbUser) {
                $this->throwException(_('Nenhum usuário foi encontrado!'));
            }

            $dbUser->destroy();
            $this->setMessage(sprintf(_('O usuário "%s" foi excluído com sucesso.'), $dbUser->name));
            $callback['success'] = true;
        } catch(AppException $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    protected function validateUser(array $data): void 
    {
        $errors = [];
        
        if(!$data['name']) {
            $errors['name'] = _('O nome é obrigatório!');
        } elseif(strlen($data['name']) > 100) {
            $errors['name'] = _('O nome precisa ter 100 caractéres ou menos!');
        }

        if(!$data['email']) {
            $errors['email'] = _('O email é obrigatório!');
        } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = _('O email é inválido!');
        } elseif(strlen($data['email']) > 100) {
            $errors['email'] = _('O email precisa ter 100 caractéres ou menos!');
        } else {
            $email = $data['id'] 
                ? (new User())
                    ->find('email = :email AND id != :id', "email={$data['email']}&id={$data['id']}")
                    ->count()
                : (new User())
                    ->find('email = :email', "email={$data['email']}")
                    ->count();

            if($email) {
                $errors['email'] = _('O email informado já está em uso! Tente outro.');
            }
        }

        if(!$data['slug']) {
            $errors['slug'] = _('O apelido é obrigatório!');
        } elseif(strlen($data['slug']) > 100) {
            $errors['slug'] = _('O apelido precisa ter 100 caractéres ou menos!');
        } else {
            $slug = $data['id'] 
                ? (new User())
                    ->find('slug = :slug AND id != :id', "slug={$data['slug']}&id={$data['id']}")
                    ->count()
                : (new User())
                    ->find('slug = :slug', "slug={$data['slug']}")
                    ->count();
            
            if($slug) {
                $errors['slug'] = _('O apelido informado já está em uso! Tente outro.');
            }
        }

        if(!$data['utip_id']) {
            $errors['utip_id'] = _('O tipo de usuário é obrigatório!');
        }

        if(($data['id'] && $data['update_password']) || !$data['id']) {
            if(!$data['password']) {
                $errors['password'] = _('A senha é obrigatória!');
            } elseif(strlen($data['password']) < 5) {
                $errors['password'] = _('A senha deve conter 5 caracteres ou mais!');
            } elseif($data['password'] && $data['confirm_password'] && $data['confirm_password'] !== $data['password']) {
                $errors['password'] = _('As senhas não correspondem!');
                $errors['confirm_password'] = _('As senhas não correspondem!');
            }

            if(!$data['confirm_password']) {
                $errors['confirm_password'] = _('A confirmação de senha é obrigatória!');
            }
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
        }
    }
}