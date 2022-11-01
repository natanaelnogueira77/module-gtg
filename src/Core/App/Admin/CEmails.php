<?php

namespace Src\Core\App\Admin;

use Src\Core\App\Admin\CTemplate;
use Src\Core\CRUD\CRUDEmailTemplate;
use Src\Core\Models\EmailTemplate;

class CEmails extends CTemplate 
{
    public function main(): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $exception = null;

        $urls = [
            'table_action' => $this->getRoute('CCAAEmails.list'),
            'creation' => $this->getRoute('CCAAEmails.creation')
        ];

        $this->loadView('page', [
            'title' => 'Emails | ' . SITE,
            'page' => [
                'title' => 'Emails',
                'subtitle' => 'Lista dos Templates de E-mail do Sistema',
                'icon' => 'pe-7s-mail-open-file',
                'icon_color' => 'bg-malibu-beach'
            ],
            'template' => $this->getTemplateView('emails', [
                'urls' => $urls
            ]),
            'scripts' => [$this->getScript('emails')],
            'exception' => $exception
        ]);
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
            
            $tableList = $this->getTableList([
                'page' => $data['p'],
                'order' => $data['ord'] ? $data['ord'] : 'name',
                'order_type' => $data['ordtype'],
                'limit' => $data['limit'],
                'search' => $data['s']
            ])->setSearchColumns(['name', 'subject', 'meta'])
                ->setClass('Src\Core\Models\EmailTemplate');
                
            if(!$tableList->find()) {
                $this->throwException($tableList->error()->getMessage());
            }

            $result = $tableList->getResults();

            $rows = [];
            if($result) {
                foreach($result as $object) {
                    $row = [];

                    $row['name'] = $object->name;
                    $row['meta'] = $object->meta;
                    $row['date_c'] = $this->getDateTime($object->date_c)->format('d/m/Y H:i');
                    $row['date_m'] = $this->getDateTime($object->date_m)->format('d/m/Y H:i');

                    $params = ['email_id' => $object->id];
                    $deleteUrl = $this->getRoute('CCAAEmails.delete', $params);

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
                                'text' => 'Editar E-mail',
                                'url' => $this->getRoute("CCAAEmails.get", $params)
                            ],
                            [
                                'type' => 'button', 
                                'text' => 'Excluir E-mail',
                                'attrs' => "data-action=\"{$deleteUrl}\" data-method=\"DELETE\" delete"
                            ]
                        ]
                    ]);

                    $rows[] = $row;
                }
            }

            $dataTable = $tableList->getDataTable($rows, [
                'actions' => ['Ações', 'actions'],
                'name' => ['Nome', 'name', true],
                'meta' => ['Chave', 'meta', true],
                'date_c' => ['Criado', 'date_c', true],
                'date_m' => ['Modificado', 'date_m', true]
            ]);
            $dataTable->noDataMsg("<div class=\"alert alert-info\">Nenhum Template de E-mail foi encontrado!</div>");
    
            $callback['content'] = $this->getView('template/table', ['table' => $dataTable->get()]);
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }

    public function get(array $data): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $exception = null;
        
        $emailData = [];

        $urls = [
            'save' => [
                'url' => $this->getRoute('CCAAEmails.create'),
                'method' => 'POST'
            ],
            'return' => $this->getRoute('CCAAEmails.main')
        ];
        
        if(isset($data['email_id'])) {
            $email = EmailTemplate::getById(intval($data['email_id']));
            if(!$email) {
                addErrorMsg('Este Template de E-mail é inexistente!');
                header('Location: ' . $urls['return']);
                exit();
            }

            $email->content = urldecode($email->content);
            $emailData = $email->getValues();

            $urls['save'] = [
                'url' => $this->getRoute('CCAAEmails.update', ['email_id' => $email->id]),
                'method' => 'PUT'
            ];
        }

        $this->loadView('page', [
            'title' => ($email ? 'Editar E-mail' : 'Criar E-mail') . ' | ' . SITE,
            'page' => [
                'title' => ($email ? "Editar E-mail \"{$email->name}\"" : 'Criar E-mail'),
                'subtitle' => 'Preencha as informações abaixo para ' 
                    . ($email ? 'editar este' : 'criar um novo') . ' Template de E-mail',
                'icon' => 'pe-7s-mail-open-file',
                'icon_color' => 'bg-malibu-beach'
            ],
            'template' => $this->getTemplateView('save-email', $emailData + [
                'urls' => $urls
            ]),
            'styles' => [$this->getStyle('save-email')],
            'scripts' => [$this->getScript('save-email')],
            'modals' => [
                $this->getView('template/modal', [
                    'modal' => [
                        'id' => 'email-preview-modal',
                        'size' => 'lg',
                        'header' => ['title' => 'Prévia'],
                        'footer' => [
                            'html' => "<button type=\"button\" class=\"btn btn-secondary btn-lg\" 
                                data-bs-dismiss=\"modal\">Fechar</button>"
                        ]
                    ]
                ])
            ],
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

            $CRUDEmailTemplate = new CRUDEmailTemplate();
            $dbEmail = $CRUDEmailTemplate->create($data + ['usu_id' => $user->id]);

            addSuccessMsg("Template de E-mail \"{$dbEmail->name}\" foi criado com sucesso!");
            $callback['link'] = $this->getRoute('CCAAEmails.get', ['email_id' => $dbEmail->id]);
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

            $CRUDEmailTemplate = new CRUDEmailTemplate();
            $dbEmail = $CRUDEmailTemplate->update(intval($data['email_id']), $data);

            $this->setMessage("Template de E-mail \"{$dbEmail->name}\" foi alterado com sucesso!");
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

            $CRUDEmailTemplate = new CRUDEmailTemplate();
            $dbEmail = $CRUDEmailTemplate->delete(intval($data['email_id']));

            $this->setMessage("O Template de E-mail \"{$dbEmail->name}\" foi excluído com sucesso.");
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }
}