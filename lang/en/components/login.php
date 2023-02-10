<?php 

return [
    'verify' => [
        'email' => [
            'required' => 'Email is required!',
            'invalid' => 'The e-mail is invalid!',
            'max' => 'Email must be 100 characters or less!'
        ],
        'password' => [
            'required' => 'Password is required!'
        ],
        'error_message' => 'Validation Errors! Check the fields.',
        'invalid_user' => 'Username or Password is invalid!'
    ]
];