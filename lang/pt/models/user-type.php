<?php 

return [
    'validate' => [
        'name_sing' => [
            'required' => 'O Nome no Singular é obrigatório!',
            'max' => 'O Nome no Singular precisa ter 45 caractéres ou menos!'
        ],
        'name_plur' => [
            'required' => 'O Nome no Plural é obrigatório!',
            'max' => 'O Nome no Plural precisa ter 45 caractéres ou menos!'
        ],
        'error_message' => 'Erros de Validação! Verifique os campos.'
    ]
];