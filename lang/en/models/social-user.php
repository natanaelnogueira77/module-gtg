<?php 

return [
    'validate' => [
        'user' => [
            'required' => 'User is required!'
        ],
        'social_id' => [
            'required' => 'Social Network ID is required!'
        ],
        'social' => [
            'required' => 'Social Network Name is required!',
            'invalid' => 'The Social Network Name is invalid!'
        ],
        'email' => [
            'required' => 'Email is required!',
            'invalid' => 'This Email is invalid!',
            'max' => 'Email must be 100 characters or less!',
            'exists' => 'This Email is already in use! Try another.'
        ],
        'error_message' => 'Validation Errors! Check the fields.'
    ]
];