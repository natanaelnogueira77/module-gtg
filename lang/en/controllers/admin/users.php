<?php 

return [
    'index' => [],
    'create' => [],
    'store' => [
        'email' => [
            'subject' => 'You have successfully registered!',
            'no_send' => 'User "{user_name}" was successfully registered!
                However, it was not possible to send a notification to his email.',
            'success' => 'User "{user_name}" was successfully created!'
        ]
    ],
    'edit' => [
        'no_user' => 'No Users found!'
    ],
    'update' => [
        'success' => 'User "{user_name}" data was successfully changed!',
        'no_user' => 'No Users found!'
    ],
    'list' => [
        'actions' => [
            'title' => 'Actions',
            'edit' => 'Edit User',
            'delete' => 'Delete User'
        ],
        'headers' => [
            'actions' => 'Actions',
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'created_at' => 'Created at'
        ]
    ],
    'delete' => [
        'success' => 'User "{user_name}" has been successfully deleted.',
        'no_user' => 'No Users found!'
    ],
    'system' => [
        'success' => 'Settings updated successfully!'
    ]
];