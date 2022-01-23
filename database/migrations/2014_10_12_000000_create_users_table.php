<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('cedula')->nullable();
            // $table->string('cedula')->unique()->nullable();
            $table->string('primer_nombre')->nullable();
            $table->string('segundo_nombre')->nullable();
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            
            $table->string('token')->nullable();
            $table->integer('tipo_usuario')->default(1)->nullable()->comment('1 Usuario, 2 Estudiante, 3 Docente, 5, Admin');
            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();
            
            $table->bigInteger('creador_fk')->nullable();
            // $table->string('email')->unique()->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario');
    }
}
