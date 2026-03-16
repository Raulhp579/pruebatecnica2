<?php

namespace Database\Seeders;

use App\Models\User_Permiso;
use Illuminate\Database\Seeder;

class User_PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        User_Permiso::create([
            "id_user"=>101,
            "id_permiso"=>2
        ]);

        User_Permiso::create([
            "id_user"=>101,
            "id_permiso"=>1
        ]);

        User_Permiso::create([
            "id_user"=>101,
            "id_permiso"=>3
        ]);
    }
}
