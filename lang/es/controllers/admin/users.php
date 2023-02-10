<?php 

return [
    'index' => [],
    'create' => [],
    'store' => [
        'email' => [
            'subject' => '¡Se ha registrado exitosamente!',
            'no_send' => '¡El usuario "{user_name}" se registró correctamente!
            Sin embargo, no fue posible enviar una notificación a su Email.',
            'success' => '¡El usuario "{user_name}" se creó correctamente!'
        ]
    ],
    'edit' => [
        'no_user' => '¡No se encontraron usuarios!'
    ],
    'update' => [
        'success' => '¡Los datos de usuario "{user_name}" se cambiaron con éxito!',
        'no_user' => '¡No se encontraron usuarios!'
    ],
    'list' => [
        'actions' => [
            'title' => 'Acciones',
            'edit' => 'Editar Usuario',
            'delete' => 'Borrar Usuario'
        ],
        'headers' => [
            'actions' => 'Acciones',
            'id' => 'ID',
            'name' => 'Nombre',
            'email' => 'Email',
            'created_at' => 'Creado en'
        ]
    ],
    'delete' => [
        'success' => 'El usuario "{user_name}" se ha eliminado correctamente.',
        'no_user' => '¡No se encontraron usuarios!'
    ],
    'system' => [
        'success' => '¡Configuración actualizada con éxito!'
    ]
];