<?php 

namespace Src\Database\Seeders;

use GTG\MVC\DB\Seeder;

class UserTypeSeeder extends Seeder 
{
    public function run(): void 
    {
        $this->exec("
            INSERT INTO usuario_tipo (id, name_sing, name_plur) VALUES 
            (1, 'Administrador', 'Administradores'), 
            (2, 'Usuário', 'Usuários');
        ");
    }
}