<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user(); //devolver el usuario autenticado
})->middleware('auth:sanctum');

Route::get('/auth', function () {
    return response()->json(['message' => 'No has iniciado sesion o credenciales invalidas'], 401);
})->name('login');

Route::prefix('v1')->group(function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    //ruta con queryparams (filtrando por estado y prioridad)
    Route::get('/tasks/filter', [TaskController::class, 'filterStatusOrPriority']);
    //agregando rutas con parametros (parametros de ruta)
    Route::get('/tasks/{taskId}', [TaskController::class, 'show']);

    //ruta para login
    Route::post('/login', [LoginController::class, 'login']);
    
    //agrupamos rutas protegidas
    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/tasks/remainin-days/{taskId}', [TaskController::class, 'remaininDays']);
        Route::patch('/tasks/{taskId}', [TaskController::class, 'update']);
        //obtener tareas del usuario autenticado
        Route::get('/user/tasks', [UserController::class, 'tasksByAuthenticatedUser']);
        //cerrar sesion
        Route::post('/logout', [LoginController::class, 'logout']);
    });
});

