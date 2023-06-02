<?php 

namespace Src\Database\Seeders;

use GTG\MVC\DB\Seeder;
use Src\Models\User;

class UserSeeder extends Seeder 
{
    public function run(): void 
    {
        User::insertMany([
            [
                'utip_id' => User::USER_TYPE_ADMIN,
                'name' => 'Admin',
                'password' => 'starter',
                'email' => 'admin@projectexample.com',
                'slug' => 'adm',
                'token' => md5('admin@projectexample.com')
            ]
        ]);
    }
}