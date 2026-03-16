<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rol::factory()->create([
            'nombre' => 'Administrador',
            'descripcion' => 'este rol tiene acceso a la funcionalidad completa del sistema',
        ]);

        Rol::factory()->create([
            'nombre' => 'Gestor',
            'descripcion' => 'este rol tiene acceso a ver todo y crear',
        ]);

        Rol::factory()->create([
            'nombre' => 'Usuario',
            'descripcion' => 'este rol tiene acceso solo a crear',
        ]);
    }
}
