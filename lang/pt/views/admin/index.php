<?php 

return [
    'title' => 'Administrador | {site_name}',
    'title2' => 'Painel do Administrador',
    'subtitle' => 'Relatórios e gerenciamento do sistema',
    'card1' => [
        'title' => 'Módulo GTG',
        'text1' => 'Versão:',
    ],
    'card2' => [
        'subheading' => '{user_type} do Sistema',
        'button' => 'Ver {user_type}'
    ],
    'card3' => [
        'title' => 'Usuários',
        'filters' => [
            'user_type' => [
                'label' => 'Nível de Usuário',
                'option0' => 'Todos os Usuários'
            ]
        ]
    ],
    'card4' => [
        'title' => 'Configurações do Sistema',
        'system' => [
            'style' => [
                'label' => 'Tema',
                'option0' => 'Escolha o Tema de Cores do Sistema...',
                'option1' => 'Tema Claro',
                'option2' => 'Tema Escuro'
            ],
            'login_img' => [
                'label' => 'Imagem de Fundo (Login)',
                'button' => 'Escolher Imagem'
            ],
            'logo' => [
                'label' => 'Logo',
                'button' => 'Escolher Imagem'
            ],
            'logo_icon' => [
                'label' => 'Ícone (Tamanho Recomendado: 512 x 512)',
                'button' => 'Escolher Imagem'
            ],
            'submit' => [
                'value' => 'Salvar Configurações'
            ]
        ]
    ],
    'script' => [
        'users' => [
            'delete' => [
                'confirm' => 'Deseja realmente excluir este Usuário?'
            ]
        ]
    ]
];