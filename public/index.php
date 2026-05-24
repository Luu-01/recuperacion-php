<?php

declare(strict_types=1);

/**
 * Front Controller.
 *
 * Único punto de entrada HTTP de la aplicación. Todas las peticiones llegan
 * aquí desde .htaccess y después se delegan al Router.
 */
require_once __DIR__ . '/../bootstrap/bootstrap.php';

try {
    router()->dispatch(request());
} catch (Throwable $e) {
    if (defined('DEBUG') && DEBUG === true) {
        d($e->getMessage());
        d($e->getTraceAsString());
        return;
    }

    http_response_code(http_response_code() === 200 ? 500 : http_response_code());
    echo 'Error de aplicación';
}
