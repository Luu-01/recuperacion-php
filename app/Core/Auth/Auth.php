<?php

declare(strict_types=1);

namespace App\Core\Auth;

use App\Core\Contracts\Authenticatable;

class Auth
{
    protected static string $authModelClass;
    protected static ?Authenticatable $user = null;

    /* ==========================================================================
       Inicialización
       ========================================================================== */

    /**
     * Inicializa el sistema de autenticación y carga el usuario autenticado
     * desde la sesión si existe.
     */
    public static function init(string $authModelClass): void
    {
        static::$authModelClass = $authModelClass;

        $id = session()->get('user_id');

        if (!$id) {
            return;
        }

        $class = static::$authModelClass;

        /** @var Authenticatable|null $user */
        $user = $class::find((int) $id);

        if ($user) {
            static::$user = $user;
        } else {
            session()->forget('user_id');
        }
    }

    /* ==========================================================================
       Métodos de consulta
       ========================================================================== */

    /**
     * Indica si existe un usuario autenticado en la petición actual.
     */
    public static function check(): bool
    {
        return static::$user !== null;
    }

    /**
     * Devuelve el usuario autenticado actual o null si no hay usuario autenticado.
     */
    public static function user(): ?Authenticatable
    {
        return static::$user;
    }

    /**
     * Devuelve el identificador del usuario autenticado o null si no existe.
     */
    public static function id(): ?int
    {
        return static::$user?->getAuthIdentifier();
    }

    /* ==========================================================================
       Métodos de acción
       ========================================================================== */

    /**
     * Intenta autenticar al usuario usando las credenciales proporcionadas.
     */
    public static function attempt(array $credentials): bool
    {
        $class = static::$authModelClass;

        /** @var Authenticatable $prototype */
        $prototype = new $class();

        $loginField = $prototype->getAuthLoginName();
        $passwordField = $prototype->getAuthPasswordName();

        $login = $credentials[$loginField] ?? null;
        $password = $credentials[$passwordField] ?? null;

        if (!$login || !$password) {
            return false;
        }

        /** @var Authenticatable|null $user */
        $user = $class::where($loginField, $login)->first();

        if (!$user || !password_verify($password, $user->getAuthPassword())) {
            return false;
        }

        static::$user = $user;
        static::login($user);

        return true;
    }

    /**
     * Autentica al usuario indicado y lo asocia a la sesión actual.
     */
    public static function login(Authenticatable $user): void
    {
        static::$user = $user;
        session()->put('user_id', $user->getAuthIdentifier());
    }

    /**
     * Limpia el estado interno de autenticación desvinculando al usuario actual
     * y eliminando la información de autenticación almacenada en la sesión
     */
    public static function logout(): void
    {
        static::$user = null;
        session()->forget('user_id');
    }
}
