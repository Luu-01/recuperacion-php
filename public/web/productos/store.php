<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap/bootstrap.php';

use App\Http\Middlewares\CsrfMiddleware;
use App\Http\Middlewares\AuthMiddleware;
use App\Http\Requests\ProductoRequest;
use App\Http\Controllers\ProductoController;

try {
    (new CsrfMiddleware())->handle();
    (new AuthMiddleware())->handle();

    $request = new ProductoRequest();
    $request->validate();
    
    (new ProductoController())->store($request);
} catch (Throwable $e) {
    echo $e->getMessage() . "<br>" . $e->getTraceAsString();
}
