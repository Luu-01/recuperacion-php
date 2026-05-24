<?php
declare(strict_types=1);

namespace App\Core\Traits;

trait Authenticatable
{
    public function getAuthIdentifierName(): string
    {
        /** @var \App\Core\Model $this */
        return static::$primaryKey;
    }

    public function getAuthIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getAuthLoginName(): string
    {
        // Por defecto "email", pero el modelo puede sobrescribirlo
        return 'email';
    }

    public function getAuthLogin(): mixed
    {
        $field = $this->getAuthLoginName();
        return $this->{$field};
    }

    public function getAuthPasswordName(): string
    {
        // Por defecto "password"
        return 'password';
    }

    public function getAuthPassword(): string
    {
        $col = $this->getAuthPasswordName();
        return $this->{$col};
    }
}
