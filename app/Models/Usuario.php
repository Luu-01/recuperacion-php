<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Auth\AuthenticatableModel;

class Usuario extends AuthenticatableModel
{
    protected static string $table = 'usuarios';
    protected static array $columns = ['id', 'nombre', 'correo', 'clave'];

    protected static array $hidden = ['clave'];

    public function getAuthLoginName(): string
    {
        return "correo";
    }

    public function getAuthPasswordName(): string
    {
        return "clave";
    }

    public function setClave(string $plain): void
    {
        $this->clave = password_hash($plain, PASSWORD_BCRYPT);
    }
}
