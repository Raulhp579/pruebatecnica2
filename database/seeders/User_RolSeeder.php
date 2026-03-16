<?php

namespace Database\Seeders;

use App\Models\User_Rol;
use Illuminate\Database\Seeder;

class User_RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User_Rol::factory()->create([
            'id_user' => 101,
            'id_rol' => 1, // admin
        ]);

        User_Rol::factory()->create([
            'id_user' => 102,
            'id_rol' => 2, // gestor
        ]);

        User_Rol::factory()->create([
            'id_user' => 103,
            'id_rol' => 3, // usuario
        ]);
    }
}
