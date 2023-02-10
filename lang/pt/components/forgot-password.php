<?php 

return [
    'verify' => [
        'email' => [
            'required' => 'O Email é obrigatório!',
            'invalid' => 'O Email é inválido!',
            'max' => 'O Email precisa ter 100 caractéres ou menos!',
            'exists' => 'Este Email não foi encontrado!'
        ],
        'error_message' => 'Erros de Validação! Verifique os campos.'
    ]
];