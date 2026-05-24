<?php

declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Http\Middlewares\CsrfMiddleware;
use App\Core\Request;
use App\Http\Controllers\AuthController;
use App\Http\Requests\LoginRequest;

try {
    $request = new Request();
    $controller = new AuthController();

    if ($request->method() === 'GET') {
        $controller->showLoginForm();
    } else {
        (new CsrfMiddleware())->handle();

        $loginRequest = new LoginRequest();
        $loginRequest->validate();
        
        $controller->login($request);
    }
} catch (Throwable $e) {
    echo $e->getMessage() . "<br>" . $e->getTraceAsString();
}
