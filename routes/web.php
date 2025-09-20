<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//peticion HTTP: GET, POST, PUT, PATCH, DELETE
Route::get('/saludo', function () {
    echo "Hola mundo desde Laravel";
});