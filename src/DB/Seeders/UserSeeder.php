<?php 

namespace Src\DB\Seeders;

use Src\DB\Seeder;
use Src\Models\AR\User;

class UserSeeder extends Seeder 
{
    public function run(): void 
    {
        User::saveMany([
            (new User())->fillAttributes([
                'user_type' => User::UT_ADMIN,
                'name' => 'Admin',
                'password' => 'example',
                'email' => 'admin@projectexample.com',
                'slug' => 'adm'
            ])
        ]);
    }
}