<?php 

return [
    'validate' => [
        'name' => [
            'required' => 'O Nome é obrigatório!',
            'max' => 'O Nome precisa ter 100 caractéres ou menos!'
        ],
        'email' => [
            'required' => 'O Email é obrigatório!',
            'invalid' => 'O Email é inválido!',
            'max' => 'O Email precisa ter 100 caractéres ou menos!',
            'exists' => 'Este Email já está em uso! Tente outro.'
        ],
        'slug' => [
            'required' => 'O Apelido é obrigatório!',
            'max' => 'O Apelido precisa ter 100 caractéres ou menos!',
            'exists' => 'Este Apelido já está em uso! Tente outro.'
        ],
        'user_type' => [
            'required' => 'O Tipo de Usuário é obrigatório!'
        ],
        'password' => [
            'required' => 'A Senha é obrigatória!',
            'min' => 'A Senha deve conter 5 caracteres ou mais!'
        ],
        'error_message' => 'Erros de Validação! Verifique os campos.'
    ]
];