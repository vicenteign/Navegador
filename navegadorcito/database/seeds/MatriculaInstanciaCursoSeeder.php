<?php

use Illuminate\Database\Seeder;

class MatriculaInstanciaCursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $matricula = factory(App\MatriculaInstanciaCurso::class, 100)->create();
    }
}
