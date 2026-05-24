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
Route::get('/', [ProductoController::class, 'index']);
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/show', [ProductoController::class, 'show']);
Route::get('/productos/create', [ProductoController::class, 'create']);
Route::get('/productos/edit', [ProductoController::class, 'edit']);
Route::post('/productos/store', [ProductoController::class, 'store']);
Route::put('/productos/update', [ProductoController::class, 'update']);
Route::delete('/productos/delete', [ProductoController::class, 'destroy']);
