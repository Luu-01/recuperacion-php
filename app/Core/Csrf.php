<?php

declare(strict_types=1);

namespace App\Core;

class Csrf
{
    public static function token(): string
    {
        if (is_null(session()->get('_token'))) {
            session()->put('_token', bin2hex(random_bytes(30)));
        }

        return session()->get('_token');
    }

    public static function validate(?string $token): bool
    {
        $sessionToken = session()->get('_token');

        if (!is_string($token) || !is_string($sessionToken)) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }


    public static function regenerateToken(): void
    {
        session()->put('_token', bin2hex(random_bytes(30)));
    }
}
