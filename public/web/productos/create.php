<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap/bootstrap.php';

use App\Http\Middlewares\AuthMiddleware;
use App\Http\Controllers\ProductoController;

try {
    (new AuthMiddleware())->handle();

    (new ProductoController())->create();
} catch (Throwable $e) {
    echo $e->getMessage() . "<br>" . $e->getTraceAsString();
}
