<?php

namespace Database\Seeders;

use App\Models\Rol_Permiso;
use Illuminate\Database\Seeder;

class Rol_PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rol administrador
        Rol_Permiso::create([
            'id_rol' => 1,
            'id_permiso' => 1,
        ]);

        Rol_Permiso::create([
            'id_rol' => 1,
            'id_permiso' => 2,
        ]);

        Rol_Permiso::create([
            'id_rol' => 1,
            'id_permiso' => 3,
        ]);

        Rol_Permiso::create([
            'id_rol' => 1,
            'id_permiso' => 4,
        ]);

        // rol gestor
        Rol_Permiso::create([
            'id_rol' => 2,
            'id_permiso' => 1,
        ]);

        Rol_Permiso::create([
            'id_rol' => 2,
            'id_permiso' => 2,
        ]);

        // rol usuario

        Rol_Permiso::create([
            'id_rol' => 3,
            'id_permiso' => 2,
        ]);

    }
}
