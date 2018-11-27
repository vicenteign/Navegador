<?php

use Faker\Generator as Faker;
use Freshwork\ChileanBundle\Rut;

$factory->define(App\MatriculaInstanciaCurso::class, function (Faker $faker) {
    do{
        $random_number = rand(1000000, 25000000);
        $rut = new Rut($random_number);
        $rut->fix()->format();
        $profesor_buscado = App\Profesor::where('rut', '=', $rut)->first();
        $alumno_buscado = App\Alumno::where('rut', '=', $rut)->first();
    } while (($profesor_buscado != null) && ($alumno_buscado != null));
    $user = factory(App\User::class)->create(['type' => 'estudiante']);
    $alumno = factory(App\Alumno::class)->create(['rut' => $rut, 'users_id_user' => $user->id]);
    return [
        'anyo' => date('Y'),
        'semestre' => Rand(1,2),
        'estado' => 'Valida',
        'alumnos_id_alumno' => $alumno->id_alumno,
        'rut' => $rut,
    ];
});
