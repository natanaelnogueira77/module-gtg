<?php 

return [
    'app_version' => ENV['app_version'],
    'app_name' => ENV['app_name'],
    'error_mail' => ENV['app_error_mail'],
    'storage_driver' => ENV['storage_driver'],
    'recaptcha' => [
        'site_key' => ENV['recaptcha_site_key'],
        'secret_key' => ENV['recaptcha_secret_key']
    ],
    'facebook' => [
        'app_id' => ENV['facebook_id'],
        'app_secret' => ENV['facebook_secret'],
        'app_version' => ENV['facebook_version']
    ],
    'google' => [
        'app_id' => ENV['google_id'],
        'app_secret' => ENV['google_secret']
    ],
    'dropbox' => [
        'key' => ENV['dropbox_key'],
        'secret' => ENV['dropbox_secret'],
        'token' => ENV['dropbox_token'],
        'folder_root' => ENV['dropbox_folder_root']
    ]
];