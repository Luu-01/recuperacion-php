<?php

require_once __DIR__ . '/../app/Core/helpers/helper.php';
require_once __DIR__ . '/../config/config.php';

use App\Core\Auth\Auth;
use App\Models\Usuario;

spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    $path = lcfirst($path);

    if (file_exists($path)) {
        require_once $path;
    }
});

session()->initFlash();
Auth::init(Usuario::class);
