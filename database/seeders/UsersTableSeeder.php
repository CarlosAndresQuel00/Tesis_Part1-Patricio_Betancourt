<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vaciar la tabla
        User::truncate();
        $faker = \Faker\Factory::create();
        // Crear la misma clave para todos los usuarios
        // conviene hacerlo antes del for para que el seeder
        // no se vuelva lento.
        $password = Hash::make('12312312');
        User::create([
            'identity' => '1715021739',
            'password' => $password,
        ]);
        // Generar algunos usuarios para nuestra aplicacion
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'identity' => $faker->numerify('17########'),
                'password' => $password,
            ]);
        }
    }
}
