<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\SideNav;
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

        Rol::factory()->create([
            'nombre' => 'Administrador',
            'descripcion' => 'este rol tiene acceso a la funcionalidad completa del sistema',
        ]);

        Rol::factory()->create([
            'nombre' => 'Usuario',
            'descripcion' => 'este rol tiene acceso limitado del sistema',
        ]);

        User::factory()->create([
            'name' => 'Raúl',
            'email' => 'raul@gmail.com',
            'password' => '12345',
        ]);

        User_Rol::factory()->create([
            'id_user' => 101,
            'id_rol' => 1,
        ]);

        User::factory()->create([
            'name' => 'Raúl2',
            'email' => 'raul2@gmail.com',
            'password' => '12345',
        ]);

        User_Rol::factory()->create([
            'id_user' => 102,
            'id_rol' => 2,
        ]);




        SideNav::create([
            "header"=>"MENÚ"
        ]);


        SideNav::create([
            "text"=>"ver usuarios",
            "url"=>"verUsuarios",
            'icon'=>"fas fa-fw fa-users",
            'id_html'=>'usuariosNav'
        ]);

        SideNav::create([
            "text"=>"proyectos",
            "url"=>"proyectos",
            'icon'=>"fas fa-fw fa-project-diagram"
        ]);

        SideNav::create([
            "header"=>"account_settings"
        ]);

        SideNav::create([
            "text"=>"profile",
            "url"=>"perfil",
            'icon'=>"fas fa-fw fa-user",
            'id_html'=>'perfilNav'
        ]);

        SideNav::create([
            "text"=>"cerrar sesión",
            "url"=>"/",
            'icon'=>"fas fa-fw fa-sign-out-alt",
        ]);

    }
}
