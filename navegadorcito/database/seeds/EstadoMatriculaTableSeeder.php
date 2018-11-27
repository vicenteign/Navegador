<?php

use Illuminate\Database\Seeder;

class EstadoMatriculaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profesores = factory(App\EstadoMatricula::class, 5)->create();
    }
}
