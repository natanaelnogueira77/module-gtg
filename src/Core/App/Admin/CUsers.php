<?php

namespace Src\Core\App\Admin;

use Src\Core\App\Admin\CTemplate;
use Src\Core\CRUD\CRUDUser;
use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\User;
use Src\Core\Models\UserType;

class CUsers extends CTemplate 
{
    public function main(): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $exception = null;

        $urls = [
            'table_action' => $this->getRoute('CCAAUsers.list'),
            'creation' => $this->getRoute('CCAAUsers.creation')
        ];

        $this->loadView('page', [
            'title' => 'Usuários | ' . SITE,
            'page' => [
                'title' => 'Lista de Usuários',
                'subtitle' => 'Segue abaixo a lista de usuários do Sistema',
                'icon' => 'pe-7s-users',
                'icon_color' => 'bg-malibu-beach'
            ],
            'template' => $this->getTemplateView('users', [
                'urls' => $urls
            ]),
            'scripts' => [$this->getScript('users')]
        ]);
    }

    public function get(array $data): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $exception = null;

        $userData = [];
        $userMetaData = [];
        $allData = [];

        $userTypes = UserType::get();
        $countries = json_decode(file_get_contents(PATH . '/public/assets/json/countries.json'));

        $urls = [
            'save' => [
                'url' => $this->getRoute('CCAAUsers.create'),
                'method' => 'POST'
            ],
            'return' => $this->getRoute('CCAAMain.main'),
            'validate_slug' => $this->getRoute('CEAWMain.validateSlug')
        ];

        if(isset($data['user_id'])) {
            $dbUser = User::getById(intval($data['user_id']));
            if(!$dbUser) {
                addErrorMsg('Este Usuário é inexistente!');
                header('Location: ' . $urls['return']);
                exit();
            }

            if($dbUser) {
                $userData = $dbUser->getValues();
            }

            $allData = array_merge($userData, $userMetaData);
            $urls['save'] = [
                'url' => $this->getRoute('CCAAUsers.update', ['user_id' => $dbUser->id]),
                'method' => 'PUT'
            ];
        }

        $this->loadView('page', [
            'title' => (isset($data['user_id']) ? 'Editar Usuário' : 'Criar Usuário') . ' | '  . SITE,
            'page' => [
                'title' => ($dbUser ? "Editar Usuário \"{$dbUser->name}\"" : 'Criar Usuário'),
                'subtitle' => 'Preencha os dados abaixo para ' . ($dbUser ? 'alterar o' : 'criar um') 
                    . " usuário, e então clique em \"Salvar Usuário\"",
                'icon' => 'pe-7s-user',
                'icon_color' => 'bg-malibu-beach'
            ],
            'template' => $this->getTemplateView('save-user', $allData + [
                'urls' => $urls,
                'userTypes' => $userTypes,
                'countries' => $countries
            ]),
            'scripts' => [$this->getScript('save-user')],
            'exception' => $exception
        ]);
    }

    public function create(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];
        
        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            $this->validateUser($data);
            
            $CRUDUser = new CRUDUser();
            $dbUser = $CRUDUser->create($data);

            $this->setEmailTemplate('user_registered');
            $logo = $this->getConfigMeta('logo');

            $this->sendEmail($dbUser->email, $dbUser->name, [
                'user_name' => $dbUser->name,
                'user_slug' => $dbUser->slug,
                'user_email' => $dbUser->email,
                'user_token' => $dbUser->token,
                'user_pass' => $data['password'],
                'logo' => url($logo)
            ]);

            addSuccessMsg("Usuário \"{$dbUser->name}\" foi criado com sucesso!");
            $callback['link'] = $this->getRoute('CCAAUsers.get', ['user_id' => $dbUser->id]);
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function update(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];
        
        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            $this->validateUser($data + ['id' => $data['user_id']]);
            $CRUDUser = new CRUDUser();
            $dbUser = $CRUDUser->update(intval($data['user_id']), $data);

            $this->setMessage("Os dados do Usuário \"{$dbUser->name}\" foram alterados com sucesso!");
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function list(array $data): void 
    {
        $data = array_merge($data, filter_input_array(INPUT_GET, FILTER_DEFAULT));
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }
            
            $userTypes = UserType::get();
            $userTypes = UserType::getGroupedBy($userTypes, 'id');
            
            $filterOptions['user_type'][] = ['Todos os Usuários', 0];
            if($userTypes) {
                foreach($userTypes as $userType) {
                    $filterOptions['user_type'][] = [$userType->name_plur, $userType->id];
                }
            }
    
            if(!isset($data['user_type']) || !$data['user_type']) {
                $data['user_type'] = 0;
            }

            $filters = $data['user_type'] == 0 ? [] : ['utip_id' => $data['user_type']];
            
            $tableList = $this->getTableList([
                'page' => $data['p'],
                'order' => $data['ord'] ? $data['ord'] : 'name',
                'order_type' => $data['ordtype'],
                'limit' => $data['limit'],
                'search' => $data['s']
            ])->setSearchColumns(['name', 'email'])
                ->setFilters($filters)
                ->setClass('Src\Core\Models\User');
            
            if(!$tableList->find()) {
                $this->throwException($tableList->error()->getMessage());
            }

            $result = $tableList->getResults();

            $rows = [];
            if($result) {
                foreach($result as $object) {
                    $row = [];
                    $row["name"] = "
                        <div class=\"widget-content p-0\">
                            <div class=\"widget-content-wrapper\">
                                <div class=\"widget-content-left mr-3\">
                                    <div class=\"widget-content-left\">
                                        <img width=\"40\" class=\"rounded-circle\" 
                                            src=\"https://www.gravatar.com/avatar/"
                                            . md5(strtolower(trim($object->email))) . "\">
                                    </div>
                                </div>
                                <div class=\"widget-content-left\">
                                    <div class=\"widget-heading\">{$object->name}</div>
                                    <div class=\"widget-subheading opacity-7\">
                                        {$userTypes[$object->utip_id]->name_sing}
                                    </div>
                                </div>
                            </div>
                        </div>
                    ";
        
                    $row['email'] = $object->email;
                    $row['date_c'] = $this->getDateTime($object->date_c)->format('d/m/Y');
                    $row['actions'] = $this->getView('template/widgets/dropdown-menu', [
                        'dir' => 'up',
                        'btn' => [
                            'size' => 'sm',
                            'color' => 'primary',
                            'text' => 'Ações'
                        ],
                        'items' => [
                            [
                                'type' => 'header', 
                                'text' => 'Ações'
                            ],
                            [
                                'type' => 'link', 
                                'text' => 'Editar Usuário',
                                'url' => $this->getRoute('CCAAUsers.get', ['user_id' => $object->id])
                            ]
                        ]
                    ]);
    
                    $rows[] = $row;
                }
            }

            $dataTable = $tableList->getDataTable($rows, [
                'actions' => ['Ações', 'actions'],
                'name' => ['Nome', 'name', true],
                'email' => ['E-mail', 'email', true],
                'date_c' => ['Data de Registro', 'date_c', true]
            ], [
                ['user_type', $filterOptions['user_type'], 'select', 'Tipo de Usuário']
            ], [
                'user_type' => $data['user_type']
            ]);
            $dataTable->noDataMsg("<div class=\"alert alert-info\">Nenhum Usuário foi encontrado!</div>");
    
            $callback['content'] = $this->getView('template/table', ['table' => $dataTable->get()]);
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }

    public function delete(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            $CRUDUser = new CRUDUser();
            $dbUser = $CRUDUser->delete(intval($data['user_id']));

            $this->setMessage("O Usuário \"{$dbUser->name}\" foi excluído com sucesso.");
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    private function validateUser(array $data): void 
    {
        $errors = [];

        if(!$data['name']) {
            $errors['name'] = 'Nome é um dado obrigatório.';
        }

        if(!$data['email']) {
            $errors['email'] = 'Email é um dado obrigatório.';
        }

        if(!$data['slug']) {
            $errors['slug'] = 'Apelido é um dado obrigatório.';
        }

        if(!$data['utip_id']) {
            $errors['utip_id'] = 'Por favor, selecione um nível para esse usuário!';
        }

        if(!$data['id'] || ($data['id'] && $data['update_password'])) {
            if(!$data['password']) {
                $errors['password'] = 'Senha é um dado obrigatório.';
            } elseif(strlen($data['password']) < 5) {
                $errors['password'] = 'Sua senha precisa ter mais de 5 caractéres!';
            } elseif($data['password'] !== $data['confirm_password']) {
                $errors['password'] = 'As senhas não coincidem!';
                $errors['confirm_password'] = 'As senhas não coincidem!';
            }
    
            if(!$data['confirm_password']) {
                $errors['confirm_password'] = 'Confirmação de Senha é um dado obrigatório.';
            }
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}