<?php

use Faker\Generator as Faker;
use Bezhanov\Faker\Provider\Educator as UniversityFaker;

$factory->define(App\Curso::class, function (Faker $faker) {
    $faker->addProvider(new UniversityFaker($faker));
    $curso = $faker->course;
    return [
        'sigla' => $faker->unique()->numerify(strtoupper(str_random(3)).'####'.'-'.'#'),
        'nombre' => $curso,
        'descripcion' => $curso,
    ];
});
