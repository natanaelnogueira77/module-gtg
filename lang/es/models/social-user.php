<?php 

return [
    'validate' => [
        'user' => [
            'required' => '¡El Usuario es obligatorio!'
        ],
        'social_id' => [
            'required' => '¡El ID de Red Social es obligatorio!'
        ],
        'social' => [
            'required' => '¡El Nombre de la Red Social es obligatorio!',
            'invalid' => '¡El Nombre de la Red Social no es válido!'
        ],
        'email' => [
            'required' => '¡El Email es obligatorio!',
            'invalid' => '¡Este email es invalido!',
            'max' => '¡El Email debe tener 100 caracteres o menos!',
            'exists' => '¡Este Email ya está en uso! Prueba otro.'
        ],
        'error_message' => '¡Errores de Validación! Revisa los campos.'
    ]
];