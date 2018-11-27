<?php

use Faker\Generator as Faker;
use Freshwork\ChileanBundle\Rut;
use Bezhanov\Faker\Provider\Educator as UniversityFaker;

$factory->define(App\InstanciaCurso::class, function (Faker $faker) {
    do{
        $random_number = rand(1000000, 25000000);
        $rut = new Rut($random_number);
        $rut->fix()->format();
        $profesor_buscado = App\Profesor::where('rut', '=', $rut)->first();
        $alumno_buscado = App\Alumno::where('rut', '=', $rut)->first();
    } while (($profesor_buscado != null) && ($alumno_buscado != null));
    $sigla = $faker->unique()->numerify(strtoupper(str_random(3)).'####'.'-'.'#');
    $curso = factory(App\Curso::class)->create(['sigla' => $sigla]);
    $profesor = factory(App\Profesor::class)->create(['rut' => $rut]);
    return [
        'sigla_curso' => $sigla,
        'anyo' => $faker->year($max = 'now'),
        'semestre' => Rand(1,2),
        'id_profesor_fk' => $profesor->id_profesor,
        'curso_id_curso' => $curso->id_curso,
    ];
});
