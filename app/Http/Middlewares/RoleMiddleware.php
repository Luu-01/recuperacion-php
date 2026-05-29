<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Core\Auth\Auth;
use App\Core\Middleware\Middleware;
use App\Core\Request;
use RuntimeException;

class RoleMiddleware implements Middleware
{
    public function handle(Request $request, ...$params): void
    {
        $requiredRole = $params[0] ?? null;
        $user = Auth::user();

        if (!$user) {
            if ($request->expectsJson()) {
                json(['error' => 'No autenticado'], 401);
            }

            redirect(url('/login'))->send();
        }

        $userRole = $user->rol ?? $user->role ?? null;

        if ($userRole !== $requiredRole) {
            if ($request->expectsJson()) {
                json(['error' => 'No autorizado'], 403);
            }

            http_response_code(403);
            throw new RuntimeException('No autorizado.');
        }
    }
}
