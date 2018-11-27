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
        $this->call(UsersTableSeeder::class);
        $this->call(MatriculaInstanciaCursoSeeder::class);
        $this->call(InstanciaCursoTableSeeder::class);
        $this->call(CursosTableSeeder::class);
        $this->call(EstadoMatriculaTableSeeder::class);
    }
}
