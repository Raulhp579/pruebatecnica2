<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\isAdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\PdfController;

Route::get('/userInfo', function (Request $request) {
    return $request->user()->rol->id_rol;
})->middleware('auth:sanctum');

Route::middleware(isAdminMiddleware::class, "auth:sanctum")->group(function () {
    Route::apiResource('user', UserController::class);
    // PDF
    Route::get('pdf/informe-tareas', [PdfController::class, 'generarPdf'])->name('pdf.informe-tareas');
   
    //tarea
    Route::put('/tarea/{id}', [TareaController::class, "update"]);
    Route::delete('/tarea/{id}', [TareaController::class, "destroy"]);

    //proyecto
    Route::post('/proyecto', [ProyectoController::class,"store"]);
    Route::put('/proyecto/{id}', [ProyectoController::class,"update"]);
    Route::delete('/proyecto/{id}', [ProyectoController::class,"destroy"]);
       
});
    
    Route::get('/proyecto', [ProyectoController::class,"index"]);

    Route::get('/tarea',[TareaController::class, "index"]);
    Route::post('/tarea', [TareaController::class, "store"]); 

    Route::get("/login", [AuthController::class, "login"]);