<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MascotaController;
use App\Http\Controllers\Api\ConsultaMascotaController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/mascotas', [MascotaController::class, 'index']);
Route::get('/razas', [MascotaController::class, 'razas']);
Route::get('/especies', [MascotaController::class, 'especies']);
Route::get('/duenos/existe-rut/{rut}', [MascotaController::class, 'existeRut']);
Route::post('/mascotas', [MascotaController::class, 'store']);
Route::get('/mascotas/{id}', [MascotaController::class, 'show']);
Route::put('/mascotas/{id}', [MascotaController::class, 'update']);
Route::delete('/mascotas/{id}', [MascotaController::class, 'destroy']);

Route::get('/mascotas-por-rut/{rut}', [ConsultaMascotaController::class, 'mascotasPorRut']);
Route::post('/registrar-atencion', [ConsultaMascotaController::class, 'registrarAtencion']);

Route::get('/atenciones/{mascota_id}', [ConsultaMascotaController::class, 'verAtenciones']);
