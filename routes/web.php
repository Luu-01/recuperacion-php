<?php

declare(strict_types=1);

use App\Core\Routing\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Middlewares\AuthMiddleware;
use App\Http\Middlewares\RoleMiddleware;

// Rutas de autenticación.
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// Rutas de productos.
// Lectura de productos: acceso libre.
Route::get('/', [ProductoController::class, 'index']);
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{producto}', [ProductoController::class, 'show']);

// Crear productos: usuario autenticado.
Route::get('/productos/create', [ProductoController::class, 'create'])
    ->middleware(AuthMiddleware::class);

Route::post('/productos', [ProductoController::class, 'store'])
    ->middleware(AuthMiddleware::class);

// Modificar y borrar productos: usuario autenticado con rol admin.
Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])
    ->middleware(AuthMiddleware::class)
    ->middleware(RoleMiddleware::class, 'admin');

Route::put('/productos/{producto}', [ProductoController::class, 'update'])
    ->middleware(AuthMiddleware::class)
    ->middleware(RoleMiddleware::class, 'admin');

Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])
    ->middleware(AuthMiddleware::class)
    ->middleware(RoleMiddleware::class, 'admin');
