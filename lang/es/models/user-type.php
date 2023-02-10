<?php 

return [
    'validate' => [
        'name_sing' => [
            'required' => '¡El Nombre en Singular es obligatorio!',
            'max' => '¡El Nombre en Singular debe tener 45 caracteres o menos!'
        ],
        'name_plur' => [
            'required' => '¡El Nombre en Plural es obligatorio!',
            'max' => '¡El Nombre en Plural debe tener 45 caracteres o menos!'
        ],
        'error_message' => '¡Errores de Validación! Revisa los campos.'
    ]
];