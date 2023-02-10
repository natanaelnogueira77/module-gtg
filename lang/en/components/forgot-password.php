<?php 

return [
    'verify' => [
        'email' => [
            'required' => 'Email is required!',
            'invalid' => 'Email is invalid!',
            'max' => 'Email must be 100 characters or less!',
            'exists' => 'This email was not found!'
        ],
        'error_message' => 'Validation Errors! Check the fields.'
    ]
];