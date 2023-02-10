<?php 

return [
    'title' => 'Usuarios | {site_name}',
    'title2' => 'Lista de Usuarios',
    'subtitle' => 'A continuación se muestra la lista de usuarios del sistema.',
    'card1' => [
        'title' => 'Usuarios',
        'create_user' => 'Crear Usuario',
        'filters' => [
            'user_type' => [
                'label' => 'Nivel de Usuario',
                'option0' => 'Todos los Usuarios'
            ]
        ]
    ],
    'script' => [
        'users' => [
            'delete' => [
                'confirm' => '¿Realmente desea eliminar este Usuario?'
            ]
        ]
    ]
];