<?php 

return [
    'validate' => [
        'name' => [
            'required' => '¡El Nombre es obligatorio!',
            'max' => '¡El Nombre debe tener 100 caracteres o menos!'
        ],
        'email' => [
            'required' => '¡El Email es requerido!',
            'invalid' => '¡El Email no es válido!',
            'max' => '¡El Email debe tener 100 caracteres o menos!',
            'exists' => '¡Este Email ya está en uso! Prueba otra.'
        ],
        'slug' => [
            'required' => '¡El Apellido es obligatorio!',
            'max' => '¡El Apellido debe tener 100 caracteres o menos!',
            'exists' => '¡Este Apellido ya está en uso! Prueba otro.'
        ],
        'user_type' => [
            'required' => '¡El Tipo de Usuario es obligatorio!'
        ],
        'password' => [
            'required' => '¡La Contraseña es obligatoria!',
            'min' => '¡La Contraseña debe contener 5 caracteres o más!'
        ],
        'error_message' => '¡Errores de Validación! Revisa los campos.'
    ]
];