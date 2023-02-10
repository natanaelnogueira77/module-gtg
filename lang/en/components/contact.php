<?php 

return [
    'send' => [
        'name' => [
            'required' => 'Name is required!',
            'max' => 'Name must be 100 characters or less!'
        ],
        'email' => [
            'required' => 'Email is required!',
            'invalid' => 'Email is invalid!',
            'max' => 'Email must be 100 characters or less!'
        ],
        'subject' => [
            'required' => 'Subject is required!',
            'max' => 'Subject must be 100 characters or less!'
        ],
        'message' => [
            'required' => 'Message is required!',
            'max' => 'Message must be 1000 characters or less!'
        ],
        'recaptcha_error' => 'You need to complete the ReCaptcha test!',
        'error_message' => 'Validation Errors! Check the fields.'
    ]
];