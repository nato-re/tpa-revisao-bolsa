<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['name' => 'Ana Corretora',  'email' => 'ana@broker.test',   'tipo' => 'vendedor'],
            ['name' => 'Bruno Corretor', 'email' => 'bruno@broker.test', 'tipo' => 'vendedor'],
            ['name' => 'Carla Investidora',  'email' => 'carla@broker.test', 'tipo' => 'cliente'],
            ['name' => 'Diego Investidor',  'email' => 'diego@broker.test', 'tipo' => 'cliente'],
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario + ['password' => 'senha123']);
        }
    }
}
