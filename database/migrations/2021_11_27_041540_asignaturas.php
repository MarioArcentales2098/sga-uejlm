<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Asignaturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('asignaturas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('color')->nullable();
            $table->string('codigo_asignatura')->nullable();
            $table->string('codigo_asignatura_num')->nullable();            
            $table->string('slug')->nullable();

            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();            
            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });

        Schema::create('asignaturas_asignacion_docentes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asignatura_fk')->nullable();
            $table->bigInteger('docente_fk')->nullable();
            $table->bigInteger('periodo_fk')->nullable();
            $table->string('token')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('asignaturas');
        Schema::dropIfExists('asignaturas_asignacion_docentes');
    }
}
