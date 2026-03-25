<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MascotaController;
use App\Http\Controllers\Api\ConsultaMascotaController;


Route::get('/mascotas', [MascotaController::class, 'index']);
Route::get('/razas', [MascotaController::class, 'razas']);
Route::post('/mascotas', [MascotaController::class, 'store']);
Route::get('/mascotas/{id}', [MascotaController::class, 'show']);
Route::put('/mascotas/{id}', [MascotaController::class, 'update']);
Route::delete('/mascotas/{id}', [MascotaController::class, 'destroy']);

Route::post('/buscar-mascota', [ConsultaMascotaController::class, 'buscarPorRutYNombre']);
Route::get('/mascotas-por-rut/{rut}', [ConsultaMascotaController::class, 'mascotasPorRut']);
Route::post('/registrar-atencion', [ConsultaMascotaController::class, 'registrarAtencion']);
