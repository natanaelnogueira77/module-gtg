<?php 

return [
    'validate' => [
        'name' => [
            'required' => 'Name is required!',
            'max' => 'Name must be 100 characters or less!'
        ],
        'email' => [
            'required' => 'Email is required!',
            'invalid' => 'Email is invalid!',
            'max' => 'Email must be 100 characters or less!',
            'exists' => 'This Email is already in use! Try another.'
        ],
        'slug' => [
            'required' => 'Nickname is required!',
            'max' => 'Nickname must be 100 characters or less!',
            'exists' => 'This Nickname is already in use! Try another.'
        ],
        'user_type' => [
            'required' => 'User Type is required!'
        ],
        'password' => [
            'required' => 'Password is required!',
            'min' => 'Password must contain 5 characters or more!'
        ],
        'error_message' => 'Validation Errors! Check the fields.'
    ]
];