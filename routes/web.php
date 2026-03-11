<?php

use App\Http\Controllers\ProfileController;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


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





Route::get('/InicioSesion', function(){
    return view('inicioSesion');
});

require __DIR__.'/auth.php';

