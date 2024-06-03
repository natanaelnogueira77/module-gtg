<?php 

$this->insert('widgets/data-table/table', [
    'activeRecordList' => $list, 
    'header' => [
        ['columnName' => 'actions', 'content' => _('Ações')],
        ['columnName' => 'name', 'content' => _('Nome'), 'isSortable' => true],
        ['columnName' => 'email', 'content' => _('Email'), 'isSortable' => true],
        ['columnName' => 'created_at', 'content' => _('Criado em'), 'isSortable' => true]
    ], 
    'body' => [
        'rows' => $models ? array_map(fn($model) => [
            $this->fetch('widgets/components/actions-dropdown', [
                'items' => [
                    [
                        'type' => 'action', 
                        'attributes' => [
                            'dt-event' => 'edit',
                            'data-method' => 'get',
                            'data-action' => $router->route('users.show', ['user_id' => $model->id]),
                            'data-modal-title' => $model->name
                        ], 
                        'content' => _('Editar Usuário')
                    ],
                    [
                        'type' => 'action', 
                        'attributes' => [
                            'dt-event' => 'delete',
                            'data-method' => 'delete',
                            'data-action' => $router->route('users.delete', ['user_id' => $model->id]),
                            'data-confirm-message' => sprintf(
                                _('Você tem certeza de que deseja excluir o usuário %s?'), 
                                $model->name
                            )
                        ], 
                        'content' => _('Excluir Usuário')
                    ],
                ]
            ]),
            $this->fetch('widgets/components/circle-avatar-title-subtitle', [
                'imageURL' => $model->getAvatarURL(),
                'title' => $model->name,
                'subtitle' => $model->getUserType()
            ]),
            $model->email,
            $model->getCreatedAtDateTime()->format('d/m/Y')
        ], $models) : null,
        'headersCount' => 4, 
        'noRowsMessage' => _('Nenhum usuário foi encontrado!')
    ]
]);