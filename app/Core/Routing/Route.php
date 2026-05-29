<?php

declare(strict_types=1);

namespace App\Core\Routing;

/**
 * Fachada estática para registrar rutas.
 *
 * Delega el registro sobre la instancia global del Router y devuelve una
 * RouteDefinition para permitir encadenamiento fluido de middlewares.
 */
class Route
{
    public static function get(string $uri, array $action): RouteDefinition
    {
        return router()->get($uri, $action);
    }

    public static function post(string $uri, array $action): RouteDefinition
    {
        return router()->post($uri, $action);
    }

    public static function put(string $uri, array $action): RouteDefinition
    {
        return router()->put($uri, $action);
    }

    public static function delete(string $uri, array $action): RouteDefinition
    {
        return router()->delete($uri, $action);
    }
}
