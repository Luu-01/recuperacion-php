<?php

declare(strict_types=1);

namespace App\Core;

class Session
{

    /** Inicia la sesión si no está ya iniciada. */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /** Obtiene un valor de la sesión. */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /** Establece un valor en la sesión. */
    public function put(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /** Verifica si la clave existe en la sesión (aunque su valor sea null). */
    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /** Verifica si existe la clave y su valor no es null. */
    public function filled(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /** Elimina una clave de la sesión. */
    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function initFlash(): void
    {
        // Recupera los datos flash de la petición anterior
        $flash = $_SESSION['_flash_next'] ?? [];

        // Accesibles en esta petición
        $_SESSION['_flash_now'] = $flash;

        // Limpia para la siguiente petición
        unset($_SESSION['_flash_next']);
    }

    /** Obtiene un valor flash para la petición actual. */
    public function getFlash(string $key, mixed $default = null): mixed
    {
        $now = $_SESSION['_flash_now'] ?? [];
        return $now[$key] ?? $default;
    }

    /** Establece un valor flash para la siguiente petición. */
    public function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash_next'][$key] = $value;
    }

    /** Verifica si existe un valor flash actual. */
    public function hasFlash(string $key): bool
    {
        return array_key_exists($key, $_SESSION['_flash_now'] ?? []);
    }

    public function old(string $key, mixed $default = ''): mixed
    {
        $old = $this->getFlash('_old');
        return $old !== null ? ($old[$key] ?? $default) : $default;
    }

    public function errors(): MessageBag
    {
        return new MessageBag($this->getFlash('_errors') ?? []);
    }

    public function error(string $key): ?string
    {
        return $this->errors()->first($key);
    }

    /** Invalida la sesión actual (logout). */
    public function invalidate(): void
    {
        session_unset();
        session_destroy();
    }

    /** Regenera el ID de sesión tras login correcto (attempt) 
     *  para evitar ataques de fijación de sesión.
     */
    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /** Regenera el token CSRF tras logout. */
    public function regenerateToken(): void
    {
        Csrf::regenerateToken();
    }
}