<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Proyecto;
use App\Models\Tarea;

class TareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un proyecto común para las tareas
        $proyecto = Proyecto::factory()->create();

        // Crear 3 tareas asociadas a ese proyecto
        Tarea::factory()->count(3)->create([
            'proyecto_id' => $proyecto->id,
        ]);
    }
}
