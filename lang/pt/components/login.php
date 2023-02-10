<?php 

return [
    'verify' => [
        'email' => [
            'required' => 'O Email é obrigatório!',
            'invalid' => 'O Email é inválido!',
            'max' => 'O Email precisa ter 100 caractéres ou menos!'
        ],
        'password' => [
            'required' => 'Senha é obrigatória!'
        ],
        'error_message' => 'Erros de Validação! Verifique os campos.',
        'invalid_user' => 'Usuário ou Senha inválidos!'
    ]
];