<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        
        $this->call(UsuarioAdminSeeder::class);
        // $this->call(EstudiantesSeeder::class);
        $this->call(ActividadesSeeder::class);
    }
}
