<?php

declare(strict_types=1);

namespace App\Core\Routing;

/**
 * Representa la definición interna de una ruta.
 *
 * Recibe la ruta por referencia para permitir una API fluida:
 * Route::delete(...)->middleware(...)->middleware(...)
 */
class RouteDefinition
{
    private array $route;

    public function __construct(array &$route)
    {
        $this->route = &$route;
    }

    public function middleware(string $middlewareClass, ...$params): self
    {
        $this->route['middlewares'][] = [$middlewareClass, ...$params];
        return $this;
    }
}
