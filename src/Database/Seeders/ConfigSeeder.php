<?php 

namespace Src\Database\Seeders;

use GTG\MVC\DB\Seeder;

class ConfigSeeder extends Seeder 
{
    public function run(): void 
    {
        $this->exec("
            INSERT INTO config (meta, value) VALUES 
            ('style', 'light'), 
            ('logo', 'storage/users/user1/logo.png'), 
            ('logo_icon', 'storage/users/user1/logo-icon.png'), 
            ('login_img', 'storage/users/user1/plain-blue-background.jpg')
        ");
    }
}