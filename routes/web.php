<?php

use App\Http\Controllers\UserController;
use App\Models\Permiso;
use App\Models\Proyecto;
use App\Models\Rol;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* Route::middleware(isAdminMiddleware::class)->group(function () {
    Route::apiResource('api/user', UserController::class);
}); */

Route::view('/crearUsuario', 'crearUsuario');
Route::get('verUsuarios', function () {
    return view('verUsuarios', ['roles'=>Rol::all()]);
});

Route::get('proyectos', function () {
    return view('proyectos', ['usuarios' => User::all(), 'proyectos' => Proyecto::all()]);
});

Route::get('/perfil', function () {
    return view('PerfilUsuario');
});

Route::get('/', function () {
    return view('inicioSesion');
});

Route::get('/registro', function () {
    return view('registro');
})->name('registro');

Route::get("/rolPermisos", function () {
    return view('rolPermisos',["permisos"=>Permiso::all()]);
});


Route::get("/tareasProyecto", function () {
    return view('tareasProyecto');
});

/* Route::get('/pruebaRol/{id}', [UserController::class, 'pruebaRol']); */

require __DIR__.'/auth.php';
