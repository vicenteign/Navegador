<?php

use Faker\Generator as Faker;

$factory->define(App\EstadoMatricula::class, function (Faker $faker) {
    $type = ['Activo', 'Finalizado'];
    return [
        'estado' => $type[Rand(0,1)],
    ];
});
