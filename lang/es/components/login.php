<?php 

return [
    'verify' => [
        'email' => [
            'required' => '¡El Email es obligatorio!',
            'invalid' => '¡El Email no es válido!',
            'max' => '¡El Email debe tener 100 caracteres o menos!'
        ],
        'password' => [
            'required' => '¡La Contraseña es obligatoria!'
        ],
        'error_message' => '¡Errores de Validación! Revisa los campos.',
        'invalid_user' => '¡El Usuario o la Contraseña no son válidos!'
    ]
];