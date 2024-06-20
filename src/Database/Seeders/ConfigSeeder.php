<?php 

namespace Database\Seeders;

use Database\Seeder;
use Models\AR\Config;

class ConfigSeeder extends Seeder 
{
    public function run(): void 
    {
        Config::saveMany([
            (new Config())->fillAttributes([
                'meta' => Config::KEY_LOGIN_IMAGE,
                'value' => 'users/user1/login-img.jpg'
            ]),
            (new Config())->fillAttributes([
                'meta' => Config::KEY_LOGO_ICON,
                'value' => 'users/user1/logo-icon.png'
            ]),
            (new Config())->fillAttributes([
                'meta' => Config::KEY_LOGO,
                'value' => 'users/user1/logo.png'
            ]),
            (new Config())->fillAttributes([
                'meta' => Config::KEY_STYLE,
                'value' => 'dark'
            ]),
        ]);
    }
}