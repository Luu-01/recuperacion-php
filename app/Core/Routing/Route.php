<?php

declare(strict_types=1);

namespace App\Core\Routing;

/**
 * Fachada estática para registrar rutas.
 *
 * Esta clase no resuelve peticiones. Solo delega el registro
 * en la instancia global del Router creada por el helper router().
 */
class Route
{
    public static function get(string $uri, array $action): void
    {
        router()->get($uri, $action);
    }

    public static function post(string $uri, array $action): void
    {
        router()->post($uri, $action);
    }

    public static function put(string $uri, array $action): void
    {
        router()->put($uri, $action);
    }

    public static function delete(string $uri, array $action): void
    {
        router()->delete($uri, $action);
    }
}
