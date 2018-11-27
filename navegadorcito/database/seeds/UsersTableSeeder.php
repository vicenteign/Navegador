<?php

use Illuminate\Database\Seeder;
use Freshwork\ChileanBundle\Rut;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin Navegadorcito',
            'email' => 'admin@localhost.com',
            'type' => 'admin',
            'password' => bcrypt('abc123456'),
        ]);
        factory(App\User::class, 5)
                    ->create(['type' => 'profesor',])
                    ->each(function ($profesores) {
                            $profesores->profesor()->save(factory(App\Profesor::class)
                                                    ->create());
                                                });
    }
}
