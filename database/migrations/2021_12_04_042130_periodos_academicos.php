<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PeriodosAcademicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('periodolectivo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->integer('anio_inicio')->nullable();
            $table->integer('anio_fin')->nullable();

            $table->integer('estado')->default(0)->comment('1 Activo, 0 inactivo')->nullable();
            $table->integer('eliminado')->default(0)->comment('1 si, 0 no')->nullable();
        });

        Schema::create('periodolectivo_quimestre', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->integer('activo')->default(0)->comment('1 Activo, 0 cerrado')->nullable();
            $table->bigInteger('aniolectivo_fk')->nullable();
        });

        Schema::create('periodolectivo_parcial', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->integer('activo')->default(0)->comment('1 Activo, 0 cerrado')->nullable();
            $table->bigInteger('quimestre_fk')->nullable();
            $table->bigInteger('periodolectivo_fk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('periodolectivo_parcial');
        Schema::dropIfExists('periodolectivo_quimestre');
        Schema::dropIfExists('periodolectivo');
    }
}
