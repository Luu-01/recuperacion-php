<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/helpers/helper.php';
require_once __DIR__ . '/autoload.php';

use App\Core\Auth\Auth;
use App\Models\Usuario;

// Inicializar la sesión y las variables flash.
session()->initFlash();

// Inicializar la autenticación.
Auth::init(Usuario::class);

// Crear la instancia del router.
router();

// Registrar las rutas de la aplicación.
require __DIR__ . '/../routes/web.php';
