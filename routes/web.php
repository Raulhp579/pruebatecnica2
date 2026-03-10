<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\PdfController;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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

Route::view('/crearUsuario', 'crearUsuario');
Route::get('verUsuarios', function () {
    return view('verUsuarios');
});

Route::get('proyectos', function () {
    return view('proyectos', ["usuarios"=>User::all(), "proyectos"=>Proyecto::all()]);
});

// Rutas API en web.php para que compartan la sesión y auth() funcione
Route::apiResource('api/user', UserController::class);
Route::apiResource('api/proyecto', ProyectoController::class);
Route::apiResource('api/tarea', TareaController::class);

// PDF
Route::get('pdf/informe-tareas', [PdfController::class, 'generarPdf'])->name('pdf.informe-tareas');

require __DIR__ . '/auth.php';
