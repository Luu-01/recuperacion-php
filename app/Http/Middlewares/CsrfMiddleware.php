<?php

namespace App\Http\Middlewares;

use App\Core\Csrf;

class CsrfMiddleware
{
    public function handle(): void
    {
        if(request()->method() === 'GET' || request()->isApiRoute()) {
            return;
        }

        $token = request()->input('_token');

        if (!Csrf::validate($token)) {
            throw new \Exception("Error de validación del token CSRF");
        }
    }
}