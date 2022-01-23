<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RolesPermisos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable(); //profesor //estudiante
            $table->string('slug')->nullable();

            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();            
            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });

        //estaticos cuales seran (modulos y acciones) 
        //pivote 1 rol tiene muchos permisos
        Schema::create('roles_has_permisos', function (Blueprint $table) {
            $table->id();
            $table->string('permiso')->nullable();
            $table->bigInteger('rol_fk')->nullable();
            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });

        //1 usuario tiene muchos roles
        Schema::create('usuarios_has_roles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rol_fk')->nullable();
            $table->bigInteger('usuario_fk')->nullable();
            
            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('roles');
        Schema::dropIfExists('roles_has_permisos');
        Schema::dropIfExists('usuarios_has_roles');
    }
}
