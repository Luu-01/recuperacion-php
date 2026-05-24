<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    protected array $server = [];

    protected array $data = [];
    protected array $get = [];
    protected array $post = [];
    protected array $body = [];

    protected array $validated = [];

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        // Cuerpo JSON (POST/PUT/PATCH/DELETE)
        if (
            in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']) &&
            str_contains($contentType, 'application/json')
        ) {
            $raw = file_get_contents('php://input');
            $this->body = json_decode($raw, true) ?: [];
        }

        // Unificar. El body JSON tiene prioridad sobre POST y GET.
        $this->data = array_merge($this->get, $this->post, $this->body);
    }

    /* -------------------------------------------------------------
        Acceso dinámico a parámetros ($request->campo)
       ------------------------------------------------------------- */

    public function __get($name): mixed
    {
        return $this->data[$name] ?? null;
    }

    public function __isset($name): bool
    {
        return array_key_exists($name, $this->data);
    }

    /* -------------------------------------------------------------
        Métodos de acceso
       ------------------------------------------------------------- */

    /** Devuelve solo parámetros de la query string (GET) */
    public function query(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) return $this->get;
        return $this->get[$key] ?? $default;
    }

    /** Devuelve solo parámetros enviados por POST */
    public function post(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) return $this->post;
        return $this->post[$key] ?? $default;
    }

    /** Devuelve GET + POST + body JSON */
    public function input(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) return $this->data;
        return $this->data[$key] ?? $default;
    }

    /* -------------------------------------------------------------
        Información del servidor / petición
       ------------------------------------------------------------- */

    /** Devuelve variables del servidor */
    public function server(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) return $this->server;
        return $this->server[$key] ?? $default;
    }

    /**
     * Devuelve el método HTTP real.
     *
     * HTML solo envía GET y POST desde formularios. Para poder registrar rutas
     * PUT y DELETE, se permite simular el método con un input oculto:
     * <input type="hidden" name="_method" value="PUT">
     */
    public function method(): string
    {
        $method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');

        if ($method === 'POST' && !empty($this->post['_method'])) {
            return strtoupper((string) $this->post['_method']);
        }

        return $method;
    }

    /* -------------------------------------------------------------
        Información de URL
       ------------------------------------------------------------- */

    /** Devuelve la URL solicitada, incluyendo el path y query string. */
    public function url(): string
    {
        return $this->server['REQUEST_URI'] ?? HOME_URL;
    }

    /** Devuelve solo el path relativo (sin query string) */
    public function path(): string
    {
        return parse_url($this->url(), PHP_URL_PATH) ?: '/';
    }

    /**
     * Devuelve la URI normalizada que usará el Router para hacer match.
     * Elimina query string y BASE_URL.
     */
    public function uri(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $uri = explode('?', $uri)[0];

        if (str_starts_with($uri, BASE_URL)) {
            $uri = substr($uri, strlen(BASE_URL));
        }

        $uri = '/' . ltrim($uri, '/');
        return $uri === '//' ? '/' : ($uri ?: '/');
    }

    /** Determina si la petición apunta a una ruta /api/ */
    public function isApiRoute(): bool
    {
        return str_contains($this->server('REQUEST_URI', ''), '/api/');
    }

    /**
     * Indica si la respuesta esperada para la petición actual es JSON.
     *
     * Dado que las rutas API siempre devuelven respuestas JSON, mientras que
     * las rutas Web devuelven vistas HTML, este método es simplemente un alias
     * de isApiRoute().
     */
    public function expectsJson(): bool
    {
        return $this->isApiRoute();
    }
}
