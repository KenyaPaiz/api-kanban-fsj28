<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    //ruta con queryparams (filtrando por estado y prioridad)
    Route::get('/tasks/filter', [TaskController::class, 'filterStatusOrPriority']);
    //agregando rutas con parametros (parametros de ruta)
    Route::get('/tasks/{taskId}', [TaskController::class, 'show']);
    
});

