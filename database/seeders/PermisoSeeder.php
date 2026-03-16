<?php

namespace Database\Seeders;

use App\Models\Permiso;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // permiso de ver todo
        Permiso::create([
            'tipo_permiso' => 0,
        ]);

        // permiso de crear
        Permiso::create([
            'tipo_permiso' => 1,
        ]);

        // permiso de modificar
        Permiso::create([
            'tipo_permiso' => 2,
        ]);

        // permiso de eliminar
        Permiso::create([
            'tipo_permiso' => 3,
        ]);
    }
}
