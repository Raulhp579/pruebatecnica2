<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Models\Proyecto;
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
    return view('verUsuarios');
});

Route::get('proyectos', function () {
    return view('proyectos', ['usuarios' => User::all(), 'proyectos' => Proyecto::all()]);
});

Route::get('/perfil', function () {
    return view('PerfilUsuario');
});



Route::get('/', function(){
    return view('inicioSesion');
});

Route::get("/registro", function(){
    return view("registro");
})->name("registro");



require __DIR__.'/auth.php';



Route::resource('/prueba', App\Http\Controllers\pruebaAutomatizacionController::class);