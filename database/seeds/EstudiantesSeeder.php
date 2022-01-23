<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EstudiantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        
        for ($i=1; $i <= 10 ; $i++) {       
            DB::table('usuario')->insert([
                'cedula' => "12345678$i",
                'primer_nombre' => "nombre1_$i",
                'segundo_nombre' => "nombre2_$i",
                'apellido_paterno' => "Apellido_$i",
                'apellido_materno' => "Materno_$i",
                'tipo_usuario' => 2,
                'email' => "estu_$i@gmail.com",
                'password' => Hash::make("12345678$i"),
                'token' => Str::random(20)
            ]);            
        }

        for ($i=1; $i <= 3 ; $i++) {       
            DB::table('usuario')->insert([
                'cedula' => "098765432$i",
                'primer_nombre' => "Docente$i",
                'segundo_nombre' => "Docente$i",
                'apellido_paterno' => "Docente$i",
                'apellido_materno' => "Docente$i",
                'tipo_usuario' => 3,
                'email' => "docente$i@gmail.com",
                'password' => Hash::make("098765432$i"),
                'token' => Str::random(20)
            ]);            
        }
    }
}
