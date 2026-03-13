<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\isAdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PerfilController;
use Illuminate\Container\Attributes\Auth;

Route::get('/userInfoRol', function (Request $request) {
    return response()->json($request->user()->rol->id_rol);
    return $request->user()->rol->id_rol;
})->middleware('auth:sanctum');

Route::get('/userInfo', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//grupo de rutas en las que debes de estar autenticado y siendo admin
Route::middleware(isAdminMiddleware::class, "auth:sanctum")->group(function () {
    Route::apiResource('user', UserController::class);
    // PDF
    Route::get('pdf/informe-tareas', [PdfController::class, 'generarPdf'])->name('pdf.informe-tareas');

    //tarea
    Route::delete('/tarea/{id}', [TareaController::class, "destroy"]);
    Route::get('/tarea', [TareaController::class, "index"]);

    //proyecto
    Route::post('/proyecto', [ProyectoController::class, "store"]);
    Route::put('/proyecto/{id}', [ProyectoController::class, "update"]);
    Route::delete('/proyecto/{id}', [ProyectoController::class, "destroy"]);
});


//grupo de rutas en las que debes de estar autenticado

Route::middleware("auth:sanctum")->group(function () {
    Route::put("/cambiarDatos", [PerfilController::class, "cambiarDatos"]);
    Route::put("/cambiarPassword", [PerfilController::class, "cambiarPassword"]);
    Route::get('/misTareas', [TareaController::class, "getByIdUser"]);
    Route::put('/tarea/{id}', [TareaController::class, "update"]);
    Route::get('/logout',[AuthController::class, 'logout']);
});



Route::get('/proyecto', [ProyectoController::class, "index"]);
Route::post('/tarea', [TareaController::class, "store"]);

Route::get("/login", [AuthController::class, "login"]);
Route::post('/registro',[AuthController::class, "register"]);


