<?php 

namespace Src\DB\Migrations;

use Src\DB\Migration;

class m0001_initial extends Migration 
{
    public function up(): void 
    {
        $this->database->createTable('config', function($table) {
            $table->id();
            $table->string('meta', 50);
            $table->text('value')->nullable();
        });

        $this->database->createTable('notificacao', function($table) {
            $table->id();
            $table->integer('usu_id');
            $table->string('content', 1000);
            $table->tinyInteger('was_read')->default('FALSE');
            $table->timestamps();
        });

        $this->database->createTable('usuario', function($table) {
            $table->id();
            $table->integer('user_type');
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('password', 100);
            $table->string('token', 100);
            $table->string('slug', 100);
            $table->string('avatar_image', 255)->nullable();
            $table->timestamps();
        });

        $this->database->createTable('usuario_meta', function($table) {
            $table->id();
            $table->integer('usu_id');
            $table->string('meta', 50);
            $table->text('value')->nullable();
        });
    }

    public function down(): void 
    {
        $this->database->dropTableIfExists('config');
        $this->database->dropTableIfExists('notificacao');
        $this->database->dropTableIfExists('usuario');
        $this->database->dropTableIfExists('usuario_meta');
    }
}