<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Calificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividades_calificaciones_matriculados', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('parcial_fk')->nullable();
            $table->bigInteger('clase_fk')->nullable();
            $table->bigInteger('curso_fk')->nullable();

            $table->bigInteger('matriculado_fk')->nullable();
            $table->bigInteger('actividad_fk')->nullable();

            $table->decimal('calificacion',8,2)->default(0)->nullable();
            $table->integer('calificado')->default(0)->comment('1 calificado, 0 no calificado')->nullable();

            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();
            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });

        Schema::create('calificaciones_parcial_matriculados', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('parcial_fk')->nullable();
            $table->bigInteger('clase_fk')->nullable();
            $table->bigInteger('curso_fk')->nullable();

            $table->bigInteger('matriculado_fk')->nullable();

            $table->decimal('calificacion',8,2)->default(0)->nullable();
            $table->integer('calificado')->default(0)->comment('1 calificado, 0 no calificado')->nullable();

            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();
            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });

        Schema::create('calificaciones_examen_quimestral_matriculados', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('quimestre_fk')->nullable();
            $table->bigInteger('clase_fk')->nullable();
            $table->bigInteger('curso_fk')->nullable();

            $table->bigInteger('matriculado_fk')->nullable();

            $table->decimal('calificacion',8,2)->default(0)->nullable();
            $table->integer('calificado')->default(0)->comment('1 calificado, 0 no calificado')->nullable();

            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();
            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });

        Schema::create('calificaciones_quimestre_matriculados', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('quimestre_fk')->nullable();
            $table->bigInteger('clase_fk')->nullable();
            $table->bigInteger('curso_fk')->nullable();

            $table->bigInteger('matriculado_fk')->nullable();

            $table->decimal('calificacion',8,2)->default(0)->nullable();
            $table->integer('calificado')->default(0)->comment('1 calificado, 0 no calificado')->nullable();

            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();
            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actividades_calificaciones_matriculados');
        Schema::dropIfExists('calificaciones_parcial_matriculados');
        Schema::dropIfExists('calificaciones_examen_quimestral_matriculados');
        Schema::dropIfExists('calificaciones_quimestre_matriculados');
    }
}
