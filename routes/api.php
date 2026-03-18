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
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RolController;
use App\Http\Middleware\puedeBorrarMiddleware;
use App\Http\Middleware\puedeCrearMiddleware;
use App\Http\Middleware\puedeEditarMiddleware;
use App\Http\Middleware\puedeVerMiddleware;

Route::get('/userInfoRol', function (Request $request) {
    return response()->json($request->user()->rol->id_rol);
/*     return $request->user()->rol->id_rol; */
})->middleware('auth:sanctum');

Route::get('/userInfo', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//grupo de rutas en las que debes de estar autenticado y siendo admin
Route::middleware(isAdminMiddleware::class, "auth:sanctum")->group(function () {
    Route::apiResource('/user', UserController::class);
    Route::apiResource('/rol',RolController::class);
    Route::apiResource('/permiso',PermisoController::class);
    Route::post('/asociarPermisoRol', [RolController::class, 'asociarPermisoRol']);
    Route::post('/asociarRolUsuario', [UserController::class, 'asociarRolUsuario']);
    Route::post('/desasociarRolUsuario', [UserController::class, 'desasociarRolUsuario']);
    Route::post("/asociarPermisoUsuario",[UserController::class, "asociarPermisoUser"]);
    Route::get("/getUserRol/{id}", [UserController::class, "getRolesUser"]);
    
});


//grupo de rutas en las que debes de estar autenticado
Route::middleware("auth:sanctum")->group(function () {
    Route::put("/cambiarDatos", [PerfilController::class, "cambiarDatos"]);
    Route::put("/cambiarPassword", [PerfilController::class, "cambiarPassword"]);
    Route::get('/misTareas', [TareaController::class, "getByIdUser"]);
    Route::get('/logout',[AuthController::class, 'logout']);
    Route::get('/proyecto', [ProyectoController::class, "index"]);
});


//grupo de rutas para crear proyectos tareas
Route::middleware("auth:sanctum", puedeCrearMiddleware::class)->group(function (){
    Route::post('/proyecto', [ProyectoController::class, "store"]);
    Route::post('/tarea', [TareaController::class, "store"]);
});

//grupo de rutas para pode ver
Route::middleware("auth:sanctum", puedeVerMiddleware::class)->group(function (){
    Route::get('pdf/informe-tareas', [PdfController::class, 'generarPdf'])->name('pdf.informe-tareas');
    Route::get('/tarea', [TareaController::class, "index"]);
});

//grupo de rutas en las que se puede editar
Route::middleware("auth:sanctum", puedeEditarMiddleware::class)->group(function (){
    Route::put('/proyecto/{id}', [ProyectoController::class, "update"]);
    Route::put('/tarea/{id}', [TareaController::class, "update"]);
});

//grupo de rutas en las que se puede eliminar
Route::middleware("auth:sanctum", puedeBorrarMiddleware::class)->group(function (){
    Route::delete('/tarea/{id}', [TareaController::class, "destroy"]);
    Route::delete('/proyecto/{id}', [ProyectoController::class, "destroy"]);
});

//rutas publicas

Route::get("/login", [AuthController::class, "login"]);
Route::post('/registro',[AuthController::class, "register"]);
Route::get('/tareasProyecto/{id}', [TareaController::class, "getByIdProyecto"]);



