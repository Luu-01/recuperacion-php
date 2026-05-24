<?php 
declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Http\Middlewares\CsrfMiddleware;
use App\Http\Middlewares\AuthMiddleware;
use App\Http\Controllers\AuthController;

try{
    (new CsrfMiddleware())->handle();
    (new AuthMiddleware())->handle();

    $controller = new AuthController();
    $controller->logout();
} catch (Throwable $e){
    echo $e->getMessage() . "<br>" . $e->getTraceAsString();
}