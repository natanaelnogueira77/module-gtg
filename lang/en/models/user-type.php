<?php 

return [
    'validate' => [
        'name_sing' => [
            'required' => 'Name in Singular is required!',
            'max' => 'Singular Name must be 45 characters or less!'
        ],
        'name_plur' => [
            'required' => 'Name in Plural is required!',
            'max' => 'Plural Name must be 45 characters or less!'
        ],
        'error_message' => 'Validation Errors! Check the fields.'
    ]
];