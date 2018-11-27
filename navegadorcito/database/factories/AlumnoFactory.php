<?php

use Faker\Generator as Faker;
use Freshwork\ChileanBundle\Rut;

$factory->define(App\Alumno::class, function (Faker $faker) {
    do{
        $random_number = rand(1000000, 25000000);
        $rut = new Rut($random_number);
        $rut->fix()->format();
        $profesor_buscado = App\Profesor::where('rut', '=', $rut)->first();
        $alumno_buscado = App\Alumno::where('rut', '=', $rut)->first();
    } while (($profesor_buscado != null) && ($alumno_buscado != null));
    return [
        'nombres' => $faker->name,
        'apellido_paterno' => $faker->LastName,
        'apellido_materno' => $faker->LastName,
        'rut' => $rut,
    ];
});
