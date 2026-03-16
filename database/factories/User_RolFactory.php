<?php

namespace Database\Factories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User_Rol>
 */
class User_RolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public $id = 0;
    public function incrementarId (){
        $this->id ++;

        return $this->id;
    }

    public function definition(): array
    {
        return [
            "id_rol"=>Rol::all()->random()->id,
            "id_user"=> $this->incrementarId()
        ];
    }
}

