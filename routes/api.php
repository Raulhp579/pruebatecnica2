<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\isAdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(isAdminMiddleware::class)->group(function () {
    Route::apiResource('user', UserController::class);
});
