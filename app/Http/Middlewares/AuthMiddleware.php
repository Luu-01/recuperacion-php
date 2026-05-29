<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Core\Auth\Auth;
use App\Core\Middleware\Middleware;
use App\Core\Request;

class AuthMiddleware implements Middleware
{
    public function handle(Request $request, ...$params): void
    {
        if (Auth::check()) {
            return;
        }

        if ($request->expectsJson()) {
            json(['error' => 'No autenticado'], 401);
        }

        redirect(url('/login'))->send();
    }
}
