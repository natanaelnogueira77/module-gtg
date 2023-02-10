<?php 

return [
    'index' => [],
    'create' => [],
    'store' => [
        'email' => [
            'subject' => 'Você se Registrou com Sucesso!',
            'no_send' => 'O Usuário "{user_name}" foi cadastrado com sucesso! 
                Porém não foi possível enviar uma notificação no email dele.',
            'success' => 'Usuário "{user_name}" foi criado com sucesso!'
        ]
    ],
    'edit' => [
        'no_user' => 'Nenhum Usuário foi encontrado!'
    ],
    'update' => [
        'success' => 'Os dados do Usuário "{user_name}" foram alterados com sucesso!',
        'no_user' => 'Nenhum Usuário foi encontrado!'
    ],
    'list' => [
        'actions' => [
            'title' => 'Ações',
            'edit' => 'Editar Usuário',
            'delete' => 'Excluir Usuário'
        ],
        'headers' => [
            'actions' => 'Ações',
            'id' => 'ID',
            'name' => 'Nome',
            'email' => 'Email',
            'created_at' => 'Criado em'
        ]
    ],
    'delete' => [
        'success' => 'O Usuário "{user_name}" foi excluído com sucesso.',
        'no_user' => 'Nenhum Usuário foi encontrado!'
    ],
    'system' => [
        'success' => 'Configurações atualizadas com sucesso!'
    ]
];