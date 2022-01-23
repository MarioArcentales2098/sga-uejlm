<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActividadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insumos = DB::table('actividades_tipos')->insertGetId([
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
            'nombre' => "Insumo",
            'slug' => "insumo",
            'estado' => 1,
            'eliminado' => 0
        ]);
        $sumatorias = DB::table('actividades_tipos')->insertGetId([
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
            'nombre' => "Sumatoria",
            'slug' => "sumatoria",
            'estado' => 1,
            'eliminado' => 0
        ]);
        // actividades
        DB::table('actividades')->insert([
            'nombre' => "Tarea",
            'slug' => "tarea",
            'porcentaje' => 20.00,
            'tipo_actividad_fk' => $insumos,
            // 'tipo_actividad_fk' => 1,
            'estado' => 1,
            'eliminado' => 0,
            'color' => "#4A8BC5",
            'max_calificacion' => 10.00,
            'abr' => "TA"
        ]);
        DB::table('actividades')->insert([
            'nombre' => "Actividad Individual en Clase",
            'slug' => "actividad-individual-en-clase",
            'porcentaje' => 20.00,
            'tipo_actividad_fk' => $insumos,
            // 'tipo_actividad_fk' => 1,
            'estado' => 1,
            'eliminado' => 0,
            'color' => "#83A570",
            'max_calificacion' => 10.00,
            'abr' => "AI"
        ]);
        DB::table('actividades')->insert([
            'nombre' => "Actividad Grupal en Clase",
            'slug' => "actividad-grupal-en-clase",
            'porcentaje' => 20.00,
            'tipo_actividad_fk' => $insumos,
            // 'tipo_actividad_fk' => 1,
            'estado' => 1,
            'eliminado' => 0,
            'color' => "#EC7F5A",
            'max_calificacion' => 10.00,
            'abr' => "AG"
        ]);
        DB::table('actividades')->insert([
            'nombre' => "Lección",
            'slug' => "lección",
            'porcentaje' => 20.00,
            'tipo_actividad_fk' => $insumos,
            // 'tipo_actividad_fk' => 1,
            'estado' => 1,
            'eliminado' => 0,
            'color' => "#7D73B2",
            'max_calificacion' => 10.00,
            'abr' => "LE"
        ]);
        DB::table('actividades')->insert([
            'nombre' => "Examen - Proyecto",
            'slug' => "examen-proyecto",
            'porcentaje' => 20.00,
            'tipo_actividad_fk' => $sumatorias,
            // 'tipo_actividad_fk' => 2,
            'estado' => 1,
            'eliminado' => 0,
            'color' => "#FFC551",
            'max_calificacion' => 10.00,
            'abr' => "EX"
        ]);
    }
}
