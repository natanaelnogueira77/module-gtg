<?php 

namespace GTG\MVC\Example\Src\Database\Seeders;

use GTG\MVC\DB\Seeder;
use Src\Models\User;

class UserSeeder extends Seeder 
{
    public function run(): void 
    {
        User::insertMany([
            [
                'user_type' => User::UT_ADMIN,
                'name' => 'Admin',
                'password' => 'password',
                'email' => 'admin@projectexample.com',
                'slug' => 'adm',
                'token' => md5('admin@projectexample.com')
            ]
        ]);
    }
}