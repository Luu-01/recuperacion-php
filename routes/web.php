<?php

declare(strict_types=1);

use App\Core\Routing\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;

// Rutas de autenticación.
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// Rutas de productos.
// Las rutas fijas van antes que las dinámicas para evitar que /create se capture como {id}.
Route::get('/', [ProductoController::class, 'index']);
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/create', [ProductoController::class, 'create']);
Route::post('/productos', [ProductoController::class, 'store']);
Route::get('/productos/{producto}', [ProductoController::class, 'show']);
Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit']);
Route::put('/productos/{producto}', [ProductoController::class, 'update']);
Route::delete('/productos/{producto}', [ProductoController::class, 'destroy']);
