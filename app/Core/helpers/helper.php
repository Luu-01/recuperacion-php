<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;
use App\Core\Request;
use App\Core\Response;
use App\Core\MessageBag;

/* ===== Core: Request, Session y Response ===== */

function request(): Request
{
    static $instance = null;
    return $instance ??= new Request();
}

function session(): Session
{
    static $instance = null;
    return $instance ??= new Session();
}

function response(): Response
{
    static $instance = null;
    return $instance ??= new Response();
}

/* ===== Vistas ===== */

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function render(string $view, array $data = []): string
{
    extract($data);
    ob_start();
    include __DIR__ . "/../../../resources/views/{$view}.php";
    return ob_get_clean();
}

function view(string $view, array $data = [], string $layout = 'layouts/app'): void
{
    $content = render($view, $data);
    $html = render($layout, array_merge($data, ['content' => $content]));

    response()->html($html)->send();
}

/* ===== Navegación ===== */

function url(string $path): string
{
    return BASE_URL . ltrim($path, '/');
}

function redirect(string $url): Response
{
    return response()->redirect($url);
}

function back(): Response
{
    $url = request()->server('HTTP_REFERER', HOME_URL);
    return response()->redirect($url);
}

/* ===== Formularios ===== */

function csrf(): string
{
    return '<input type="hidden" name="_token" value="' . Csrf::token() . '">';
}

function old(string $name, mixed $default = ''): mixed
{
    return session()->old($name, $default);
}

function errors(): MessageBag
{
    return session()->errors();
}

function error(string $key): ?string
{
    return errors()->first($key);
}

/* ===== JSON ===== */

function json(mixed $data, int $status = 200): void
{
    response()->status($status)->json($data)->send();
}

/* ===== Depuración ===== */

function d(mixed $var): void
{
    echo "<pre>" . print_r($var, true) . "</pre>";
}

function dd(mixed $var): void
{
    d($var);
    die;
}
