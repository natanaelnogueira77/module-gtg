<?php 

return [
    'send' => [
        'name' => [
            'required' => '¡El Nombre es obligatorio!',
            'max' => '¡El Nombre debe tener 100 caracteres o menos!'
        ],
        'email' => [
            'required' => '¡El Email es obligatorio!',
            'invalid' => '¡El Email no es válido!',
            'max' => '¡El Email debe tener 100 caracteres o menos!'
        ],
        'subject' => [
            'required' => '¡El Asunto es obligatorio!',
            'max' => '¡El Asunto debe tener 100 caracteres o menos!'
        ],
        'message' => [
            'required' => '¡El Mensaje es obligatorio!',
            'max' => '¡El Mensaje debe tener 1000 caracteres o menos!'
        ],
        'recaptcha_error' => '¡Necesitas completar la prueba ReCaptcha!',
        'error_message' => '¡Errores de Validación! Revisa los campos.'
    ]
];