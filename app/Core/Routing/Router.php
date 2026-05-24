<?php

declare(strict_types=1);

namespace App\Core\Routing;

use App\Core\Request;
use RuntimeException;

/**
 * Router básico de la aplicación.
 *
 * Guarda las rutas agrupadas por método HTTP y ejecuta la acción
 * del controlador asociada a la URI de la petición actual.
 */
class Router
{
    /**
     * Estructura interna:
     * [
     *     'GET' => [
     *         '/productos' => [ProductoController::class, 'index'],
     *     ],
     *     'POST' => [...]
     * ]
     */
    private array $routes = [];

    public function get(string $uri, array $action): void
    {
        $this->add('GET', $uri, $action);
    }

    public function post(string $uri, array $action): void
    {
        $this->add('POST', $uri, $action);
    }

    public function put(string $uri, array $action): void
    {
        $this->add('PUT', $uri, $action);
    }

    public function delete(string $uri, array $action): void
    {
        $this->add('DELETE', $uri, $action);
    }

    private function add(string $method, string $uri, array $action): void
    {
        $this->routes[$method][$this->normalize($uri)] = $action;
    }

    /**
     * Punto de entrada del router.
     * Obtiene método y URI, busca coincidencia y ejecuta el controlador.
     */
    public function dispatch(Request $request): mixed
    {
        $method = $request->method();
        $uri = $this->normalize($request->uri());

        $action = $this->routes[$method][$uri] ?? [];

        if (empty($action)) {
            http_response_code(404);
            throw new RuntimeException("404 Not Found: {$method} {$uri}");
        }

        return $this->callAction($action, $request);
    }

    private function callAction(array $action, Request $request): mixed
    {
        [$controller, $method] = $action;

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            throw new RuntimeException("Controller action not found: {$controller}::{$method}");
        }

        return $instance->$method($request);
    }

    private function normalize(string $uri): string
    {
        $uri = '/' . trim($uri, '/');
        return $uri === '/' ? '/' : rtrim($uri, '/');
    }
}
