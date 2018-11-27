<?php

use Faker\Generator as Faker;
use Freshwork\ChileanBundle\Rut;

$factory->define(App\Profesor::class, function (Faker $faker) {
    do{
        $random_number = rand(1000000, 25000000);
        $rut = new Rut($random_number);
        $rut->fix()->format();
        $profesor_buscado = App\Profesor::where('rut', '=', $rut)->first();
        $alumno_buscado = App\Alumno::where('rut', '=', $rut)->first();
    } while (($profesor_buscado != null) && ($alumno_buscado != null));
    $user = factory(App\User::class)->create(['type' => 'profesor']);
    return [
        'nombres' => $faker->name,
        'apellido_paterno' => $faker->LastName,
        'apellido_materno' => $faker->LastName,
        'rut' => $rut,
        'user_id' => $user->id,
    ];
});
