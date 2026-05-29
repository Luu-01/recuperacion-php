<?php

declare(strict_types=1);

namespace App\Core\Routing;

use App\Core\FormRequest;
use App\Core\Middleware\Middleware;
use App\Core\Model;
use App\Core\Request;
use ReflectionMethod;
use RuntimeException;

/**
 * Router de la aplicación.
 *
 * Registra rutas por método HTTP, permite rutas dinámicas, ejecuta middlewares
 * y resuelve automáticamente los argumentos del controlador usando Reflection.
 */
class Router
{
    /**
     * Estructura interna:
     * [
     *     'GET' => [
     *         '/productos/create' => [
     *             'action' => [ProductoController::class, 'create'],
     *             'middlewares' => []
     *         ],
     *     ],
     * ]
     */
    private array $routes = [];

    /** @var array<int, array<int, mixed>> */
    private array $globalMiddlewares = [];

    public function get(string $uri, array $action): RouteDefinition
    {
        return $this->add('GET', $uri, $action);
    }

    public function post(string $uri, array $action): RouteDefinition
    {
        return $this->add('POST', $uri, $action);
    }

    public function put(string $uri, array $action): RouteDefinition
    {
        return $this->add('PUT', $uri, $action);
    }

    public function delete(string $uri, array $action): RouteDefinition
    {
        return $this->add('DELETE', $uri, $action);
    }

    public function addGlobalMiddleware(string $middlewareClass, ...$params): void
    {
        $this->globalMiddlewares[] = [$middlewareClass, ...$params];
    }

    private function add(string $method, string $uri, array $action): RouteDefinition
    {
        $uri = $this->normalize($uri);

        $this->routes[$method][$uri] = [
            'action' => $action,
            'middlewares' => [],
        ];

        return new RouteDefinition($this->routes[$method][$uri]);
    }

    /**
     * Punto de entrada del router.
     *
     * Convierte cada ruta registrada a expresión regular, compara con la URI
     * actual, ejecuta middlewares y finalmente delega al controlador.
     */
    public function dispatch(Request $request): mixed
    {
        $method = $request->method();
        $uri = $this->normalize($request->uri());

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $routeUri => $definition) {
            $pattern = $this->toRegex($routeUri);

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                $this->runMiddlewares($this->globalMiddlewares, $request);
                $this->runMiddlewares($definition['middlewares'], $request);

                return $this->callAction($definition['action'], $request, $matches);
            }
        }

        http_response_code(404);
        throw new RuntimeException("404 Not Found: {$method} {$uri}");
    }

    private function runMiddlewares(array $middlewares, Request $request): void
    {
        foreach ($middlewares as $middlewareDefinition) {
            $middlewareClass = array_shift($middlewareDefinition);

            /** @var Middleware $middleware */
            $middleware = new $middlewareClass();
            $middleware->handle($request, ...$middlewareDefinition);
        }
    }

    private function callAction(array $action, Request $request, array $routeParams): mixed
    {
        [$controller, $method] = $action;

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            throw new RuntimeException("Controller action not found: {$controller}::{$method}");
        }

        $reflectionMethod = new ReflectionMethod($instance, $method);
        $params = $reflectionMethod->getParameters();
        $args = [];

        foreach ($params as $param) {
            $type = $param->getType();
            $className = $type?->getName();

            /** 1. Inyección de Request */
            if ($className === Request::class) {
                $args[] = $request;
                continue;
            }

            /** 2. Inyección de FormRequest + validación automática */
            if ($type && is_string($className) && is_subclass_of($className, FormRequest::class)) {
                $formRequest = $className::fromRequest($request);
                $formRequest->validate();
                $args[] = $formRequest;
                continue;
            }

            /** 3. Route Model Binding */
            if ($type && is_string($className) && is_subclass_of($className, Model::class)) {
                if (!empty($routeParams)) {
                    $id = array_shift($routeParams);
                    $model = $className::find((int) $id)
                        ?? throw new RuntimeException('Modelo no encontrado');

                    $args[] = $model;
                    continue;
                }
            }

            /** 4. Parámetros escalares desde la URL */
            if (!empty($routeParams)) {
                $value = array_shift($routeParams);

                if ($type && is_string($className)) {
                    settype($value, $className);
                }

                $args[] = $value;
                continue;
            }

            /** 5. Fallback */
            $args[] = null;
        }

        return $reflectionMethod->invokeArgs($instance, $args);
    }

    private function toRegex(string $uri): string
    {
        $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $uri);
        return '#^' . $pattern . '$#';
    }

    private function normalize(string $uri): string
    {
        $uri = '/' . trim($uri, '/');
        return $uri === '/' ? '/' : rtrim($uri, '/');
    }
}
