<?php 

namespace GTG\MVC\Example\Src\Database\Migrations;

use GTG\MVC\DB\Migration;

class m0001_initial extends Migration 
{
    public function up(): void
    {
        $this->db->createTable('usuario', function ($table) {
            $table->id();
            $table->integer('user_type');
            $table->string('name', 50);
            $table->string('email', 100);
            $table->string('password', 100);
            $table->string('token', 100);
            $table->string('slug', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->db->dropTableIfExists('usuario');
    }
}