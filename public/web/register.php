<?php 
declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap/bootstrap.php';

use App\Http\Middlewares\CsrfMiddleware;
use App\Http\Controllers\AuthController;
use App\Core\Request;
use App\Http\Requests\RegisterRequest;

try{
    $request = new Request();
    $controller = new AuthController();

    if($request->method() === 'GET'){
        $controller->showRegistrationForm();
    } else {
        (new CsrfMiddleware())->handle();

        $registerRequest = new RegisterRequest();
        $registerRequest->validate();
        
        $controller->register($registerRequest);
    }
    
} catch (Throwable $e){
    echo $e->getMessage() . "<br>" . $e->getTraceAsString();
}