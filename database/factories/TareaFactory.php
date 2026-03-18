<?php

namespace Database\Factories;

use App\Enums\Estado;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tarea>
 */
class TareaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descripcion' => $this->faker->sentence(),
            'tiempo_inicio' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'tiempo_fin' => $this->faker->dateTimeBetween('now', '+1 month'),
            'proyecto_id' => Proyecto::factory(),
            'id_user' => User::factory(),
            'prioridad' => $this->faker->numberBetween(1, 4),
            'estado' => $this->faker->randomElement(Estado::cases())->value,
        ];
    }
}
