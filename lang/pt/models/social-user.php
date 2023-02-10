<?php 

return [
    'validate' => [
        'user' => [
            'required' => 'O Usuário é obrigatório!'
        ],
        'social_id' => [
            'required' => 'O ID da Rede Social é obrigatório!'
        ],
        'social' => [
            'required' => 'O Nome da Rede Social é obrigatório!',
            'invalid' => 'O Nome da Rede Social é inválido!'
        ],
        'email' => [
            'required' => 'O Email é obrigatório!',
            'invalid' => 'Este Email é inválido!',
            'max' => 'O Email precisa ter 100 caractéres ou menos!',
            'exists' => 'Este Email já está em uso! Tente outro.'
        ],
        'error_message' => 'Erros de Validação! Verifique os campos.'
    ]
];