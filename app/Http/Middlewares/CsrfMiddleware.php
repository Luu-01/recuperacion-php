<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Core\Csrf;
use App\Core\Middleware\Middleware;
use App\Core\Request;
use RuntimeException;

class CsrfMiddleware implements Middleware
{
    public function handle(Request $request, ...$params): void
    {
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return;
        }

        if (!Csrf::validate($request->input('_token'))) {
            http_response_code(419);
            throw new RuntimeException('Token CSRF no válido.');
        }
    }
}
