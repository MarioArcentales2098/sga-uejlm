<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date_actual = date('Y-m-d H:i:s');
        $cedula = '1234567890';

        DB::table('usuario')->insert([
            'cedula' => $cedula,
            'primer_nombre' => "Administrador",
            'segundo_nombre' => "Administrador",
            'apellido_paterno' => "Administrador",
            'apellido_materno' => "Administrador",
            'token' => Str::random(20),
            'tipo_usuario' => 5,   
            'estado' => 1,   
            'eliminado' => 0,           
            'email' => 'administrador@sistema.com',
            'password' => Hash::make($cedula),
            'created_at' =>  $date_actual
        ]);
    }
}
