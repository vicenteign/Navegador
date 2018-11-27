<?php

use Illuminate\Database\Seeder;

class InstanciaCursoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cursos = factory(App\InstanciaCurso::class, 15)->create();
    }
}
