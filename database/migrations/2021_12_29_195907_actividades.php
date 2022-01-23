<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Actividades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividades_tipos', function (Blueprint $table) {
            $table->id();

            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();

            $table->string('nombre')->nullable();
            $table->string('slug')->nullable();

            $table->string('token')->nullable();

            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();
            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });

        Schema::create('actividades', function (Blueprint $table) {
            $table->id();

            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();

            $table->string('abr')->nullable();
            $table->string('nombre')->nullable();
            $table->string('slug')->nullable();
            $table->string('color')->nullable();

            $table->decimal('max_calificacion',8,2)->default(0)->nullable();
            $table->decimal('porcentaje',8,2)->default(0)->nullable();

            $table->string('token')->nullable();

            $table->bigInteger('tipo_actividad_fk')->nullable();

            $table->integer('estado')->default(1)->comment('1 Activo, 0 Bloqueado')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();

            $table->timestamps();
            $table->bigInteger('creador_fk')->nullable();
        });

        Schema::create('actividades_parcial', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('actividad_fk')->nullable();
            $table->bigInteger('parcial_fk')->nullable();
            $table->bigInteger('clase_fk')->nullable();
            $table->bigInteger('curso_fk')->nullable();

            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();

            $table->date('fecha_actividad')->nullable();

            $table->string('nombre')->nullable();
            $table->string('slug')->nullable();
            $table->string('descripcion')->nullable();

            $table->string('token')->nullable();

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
        //
        Schema::dropIfExists('actividades_tipos');
        Schema::dropIfExists('actividades');
        Schema::dropIfExists('actividades_parcial');
    }
}
