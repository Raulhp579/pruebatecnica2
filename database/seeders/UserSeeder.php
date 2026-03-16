<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Raúl',
            'email' => 'raul@gmail.com',
            'password' => '12345',
        ]);

        User::factory()->create([
            'name' => 'Raúl2',
            'email' => 'raul2@gmail.com',
            'password' => '12345',
        ]);

        User::factory()->create([
            'name' => 'usuario',
            'email' => 'usuario@gmail.com',
            'password' => '12345',
        ]);
    }
}
