<?php

declare(strict_types=1);

/**
 * Autoload PSR-4 básico para el namespace App.
 * Convierte App\Core\Request en app/Core/Request.php.
 */
spl_autoload_register(function (string $class): void {
    $path = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    $path = lcfirst($path);

    if (file_exists($path)) {
        require_once $path;
    }
});
