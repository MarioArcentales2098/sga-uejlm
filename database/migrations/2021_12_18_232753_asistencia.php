<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Asistencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('asistencia_clase', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('estudiante_fk')->nullable();
            $table->bigInteger('parcial_fk')->nullable();
            $table->bigInteger('clase_fk')->nullable();
            $table->bigInteger('curso_fk')->nullable();

            $table->date('fecha')->nullable();

            $table->integer('asistencia')->default(1)->comment('1 si asistio, 0 no asistio')->nullable();
            $table->integer('asistencia_justificada')->comment('1 si, 0 no')->nullable();

            $table->string('token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('asistencia_clase');
    }
}
