<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\User_Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */

        $this->call(UserSeeder::class);
        $this->call(RolSeeder::class);
        /* User_Rol::factory(100)->create(); */
        $this->call(User_RolSeeder::class);
        $this->call(SideNavSeeder::class);
        $this->call(PermisoSeeder::class);
        $this->call(Rol_PermisoSeeder::class);
        $this->call(User_PermisoSeeder::class);

    }
}
