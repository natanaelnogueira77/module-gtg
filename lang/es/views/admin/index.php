<?php 

return [
    'title' => 'Administrador | {site_name}',
    'title2' => 'Panel de Administrador',
    'subtitle' => 'Informes y gestión del sistema',
    'card1' => [
        'title' => 'Módulo GTG',
        'text1' => 'Versión:',
    ],
    'card2' => [
        'subheading' => '{user_type} del Sistema',
        'button' => 'Ver {user_type}'
    ],
    'card3' => [
        'title' => 'Usuarios',
        'filters' => [
            'user_type' => [
                'label' => 'Nivel de Usuario',
                'option0' => 'Todos los Usuarios'
            ]
        ]
    ],
    'card4' => [
        'title' => 'Ajustes del Sistema',
        'system' => [
            'style' => [
                'label' => 'Tema',
                'option0' => 'Elija el Tema de Color del Sistema...',
                'option1' => 'Tema Ligero',
                'option2' => 'Tema Oscuro'
            ],
            'login_img' => [
                'label' => 'Imagen de Fondo (Login)',
                'button' => 'Elegir Imagen'
            ],
            'logo' => [
                'label' => 'Logo',
                'button' => 'Elegir Imagen'
            ],
            'logo_icon' => [
                'label' => 'Icono (Tamanho Recomendado: 512 x 512)',
                'button' => 'Elegir Imagen'
            ],
            'submit' => [
                'value' => 'Guardar Ajustes'
            ]
        ]
    ],
    'script' => [
        'users' => [
            'delete' => [
                'confirm' => '¿Realmente desea eliminar este usuario?'
            ]
        ]
    ]
];