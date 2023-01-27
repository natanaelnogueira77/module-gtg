<?php

namespace Src\App\Controllers\Admin;

use Src\App\Controllers\Admin\Template;
use Src\Components\Email;
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
            $dbUser = new User();
            $dbUser->setValues([
                'utip_id' => $data['utip_id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'slug' => $data['slug']
            ]);
            $dbUser->save();

            $email = new Email();
            $email->add('Você se Registrou com Sucesso!', $this->getView('emails/user-register', [
                'user' => $dbUser,
                'password' => $data['password'],
                'logo' => url(Config::getMetaByName('logo'))
            ]), $dbUser->name, $dbUser->email);

            if(!$email->send()) {
                addSuccessMsg("O Usuário \"{$dbUser->name}\" foi criado com sucesso! 
                    Porém não foi possível enviar no seu email.");
            } else {
                addSuccessMsg("Usuário \"{$dbUser->name}\" foi criado com sucesso!");
            }

            $callback['link'] = $this->getRoute('admin.users.edit', ['user_id' => $dbUser->id]);
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function edit(array $data): void 
    {
        $this->addData();

        $dbUser = (new User())->findById(intval($data['user_id']));
        if(!$dbUser) {
            addErrorMsg('Nenhum Usuário foi encontrado!');
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
                $this->throwException('Nenhum Usuário foi encontrado!');
            }

            $dbUser->setValues([
                'utip_id' => $data['utip_id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['update_password'] ? $data['password'] : $dbUser->password,
                'slug' => $data['slug']
            ]);
            $dbUser->save();

            $this->setMessage('Os dados do Usuário "' . $dbUser->name . '" foram alterados com sucesso!');
        } catch(\Exception $e) {
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
                                    Ações
                                </button>
                                <div tabindex=\"-1\" role=\"menu\" aria-hidden=\"true\" class=\"dropdown-menu\">
                                    <h6 tabindex=\"-1\" class=\"dropdown-header\">Ações</h6>
                                    <a href=\"{$this->getRoute('admin.users.edit', $params)}\" 
                                        type=\"button\" tabindex=\"0\" class=\"dropdown-item\">
                                        Editar Usuário
                                    </a>

                                    <button type=\"button\" tabindex=\"0\" class=\"dropdown-item\" 
                                        data-act=\"delete\" data-method=\"delete\" 
                                        data-action=\"{$this->getRoute('admin.users.delete', $params)}\">
                                        Excluir Usuário
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
                        'actions' => ['text' => 'Ações'],
                        'id' => ['text' => 'ID', 'sort' => true],
                        'name' => ['text' => 'Nome', 'sort' => true],
                        'email' => ['text' => 'Descrição', 'sort' => true],
                        'created_at' => ['text' => 'Criado em', 'sort' => true]
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
        } catch(\Exception $e) {
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
                $this->throwException('Nenhum Usuário foi encontrado!');
            }

            $dbUser->destroy();
            $this->setMessage('O Usuário "' . $dbUser->name . '" foi excluído com sucesso.');
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }
}