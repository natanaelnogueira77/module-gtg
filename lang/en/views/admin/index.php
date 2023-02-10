<?php 

return [
    'title' => 'Administrator | {site_name}',
    'title2' => 'Administrator Panel',
    'subtitle' => 'System reporting and management',
    'card1' => [
        'title' => 'Module GTG',
        'text1' => 'Version:',
    ],
    'card2' => [
        'subheading' => "System's {user_type}",
        'button' => 'Check {user_type}'
    ],
    'card3' => [
        'title' => 'Users',
        'filters' => [
            'user_type' => [
                'label' => 'User Type',
                'option0' => 'All Users'
            ]
        ]
    ],
    'card4' => [
        'title' => 'System Settings',
        'system' => [
            'style' => [
                'label' => 'Theme',
                'option0' => 'Choose System Color Theme...',
                'option1' => 'Light Theme',
                'option2' => 'Dark Theme'
            ],
            'login_img' => [
                'label' => 'Background Image (Login)',
                'button' => 'Choose Image'
            ],
            'logo' => [
                'label' => 'Logo',
                'button' => 'Choose Image'
            ],
            'logo_icon' => [
                'label' => 'Icon (Tamanho Recomendado: 512 x 512)',
                'button' => 'Choose Image'
            ],
            'submit' => [
                'value' => 'Save Settings'
            ]
        ]
    ],
    'script' => [
        'users' => [
            'delete' => [
                'confirm' => 'Do you really want to delete this User?'
            ]
        ]
    ]
];