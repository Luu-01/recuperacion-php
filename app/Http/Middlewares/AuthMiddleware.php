<?php

namespace App\Http\Middlewares;

use App\Core\Auth\Auth;

class AuthMiddleware
{
    public function handle(): void
    {
        if (!Auth::check()) {

            if (request()->expectsJson()) {
                json(['error' => 'No autenticado'], 401);
            }

            redirect('/login.php')->send();
        }
    }
}

