<?php 

namespace Src\Database\Seeders;

use GTG\MVC\DB\Seeder;

class UserSeeder extends Seeder 
{
    public function run(): void 
    {
        $this->exec('
            INSERT INTO usuario (id, utip_id, name, password, email, slug, token) VALUES 
            (1, 1, "Admin", "$2y$10$ySA6iwTKmK.yfnNTvy3jWuG7jWUSaX.Neu93m5OdNJJtgndviO/ti", "admin@projectexample.com", "adm", "");
        ');
    }
}