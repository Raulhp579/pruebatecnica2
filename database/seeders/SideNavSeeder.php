<?php

namespace Database\Seeders;

use App\Models\SideNav;
use Illuminate\Database\Seeder;

class SideNavSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        SideNav::create([
            'header' => 'MENÚ',
        ]);

        SideNav::create([
            'text' => 'ver usuarios',
            'url' => 'verUsuarios',
            'icon' => 'fas fa-fw fa-users',
            'id_html' => 'usuariosNav',
        ]);

        SideNav::create([
            'text' => 'proyectos',
            'url' => 'proyectos',
            'icon' => 'fas fa-fw fa-project-diagram',
        ]);

        SideNav::create([
            'text' => 'RolPermisos',
            'url' => 'rolPermisos',
            'icon' => 'fas fa-fw fa-project-diagram',
        ]);

        SideNav::create([
            'header' => 'account_settings',
        ]);

        SideNav::create([
            'text' => 'profile',
            'url' => 'perfil',
            'icon' => 'fas fa-fw fa-user',
            'id_html' => 'perfilNav',
        ]);

        SideNav::create([
            'text' => 'cerrar sesión',
            'url' => '/',
            'icon' => 'fas fa-fw fa-sign-out-alt',
        ]);
    }
}
