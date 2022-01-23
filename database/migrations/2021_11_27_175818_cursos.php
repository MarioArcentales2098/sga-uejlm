<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cursos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable(); //profesor //estudiante
            $table->string('slug')->nullable();

            $table->string('nivel')->nullable();
            $table->string('paralelo')->nullable();

            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();            
            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });

        Schema::create('cursos_asignacion_materias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asignatura_fk')->nullable();
            $table->bigInteger('curso_fk')->nullable();
            $table->bigInteger('asign_docmateria_fk')->nullable()->comment('id de la tabla asignacion de materias a docentes x periodo');
            $table->bigInteger('periodo_fk')->nullable();
            $table->string('token')->nullable();
        });

        Schema::create('matricula', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_matricula')->nullable();

            $table->bigInteger('usuario_fk')->nullable();
            $table->bigInteger('periodo_fk')->nullable()->comment('fk de año lectivo (2021-2022 etc..)');
            $table->bigInteger('curso_fk')->nullable();

            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('cursos_asignacion_materias');
        Schema::dropIfExists('matricula');
    }
}
