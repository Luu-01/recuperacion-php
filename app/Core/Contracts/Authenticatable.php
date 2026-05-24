<?php
declare(strict_types=1);

namespace App\Core\Contracts;

interface Authenticatable
{
    /** Devuelve el nombre del campo que actúa como clave primaria del modelo. */
    public function getAuthIdentifierName(): string;

    /** Devuelve el valor de la clave primaria del usuario autenticable. */
    public function getAuthIdentifier(): mixed;

    /** Devuelve el nombre del campo usado para identificar al usuario en el login. */
    public function getAuthLoginName(): string;

    /** Devuelve el valor del campo de login del usuario. */
    public function getAuthLogin(): mixed;

    /** Devuelve el nombre del campo donde se almacena el hash de la contraseña. */
    public function getAuthPasswordName(): string;

    /** Devuelve el hash de la contraseña del usuario. */
    public function getAuthPassword(): string;
}
