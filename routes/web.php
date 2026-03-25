<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/buscar-mascota', function () {
    return view('app');
});

Route::get('/registrar-atencion', function () {
    return view('app');
});

Route::get('/mascotas-por-rut', function () {
    return view('app');
});