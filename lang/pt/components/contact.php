<?php 

return [
    'send' => [
        'name' => [
            'required' => 'O Nome é obrigatório!',
            'max' => 'O Nome precisa ter 100 caractéres ou menos!'
        ],
        'email' => [
            'required' => 'O Email é obrigatório!',
            'invalid' => 'O Email é inválido!',
            'max' => 'O Email precisa ter 100 caractéres ou menos!'
        ],
        'subject' => [
            'required' => 'O Assunto é obrigatório!',
            'max' => 'O Assunto precisa ter 100 caractéres ou menos!'
        ],
        'message' => [
            'required' => 'A Mensagem é obrigatória!',
            'max' => 'A Mensagem precisa ter 1000 caractéres ou menos!'
        ],
        'recaptcha_error' => 'Você precisa completar o teste do ReCaptcha!',
        'error_message' => 'Erros de Validação! Verifique os campos.'
    ]
];