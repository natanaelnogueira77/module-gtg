<?php 

namespace Src\Database\Seeders;

use GTG\MVC\DB\Seeder;
use Src\Models\Config;

class ConfigSeeder extends Seeder 
{
    public function run(): void 
    {
        Config::insertMany([
            [
                'meta' => Config::KEY_STYLE, 
                'value' => 'light'
            ],
            [
                'meta' => Config::KEY_LOGO, 
                'value' => 'users/user1/logo.png'
            ],
            [
                'meta' => Config::KEY_LOGO_ICON, 
                'value' => 'users/user1/logo-icon.png'
            ],
            [
                'meta' => Config::KEY_LOGIN_IMG, 
                'value' => 'users/user1/login-img.jpg'
            ]
        ]);
    }
}