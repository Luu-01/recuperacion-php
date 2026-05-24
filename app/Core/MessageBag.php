<?php

declare(strict_types=1);

namespace App\Core;

class MessageBag
{
    /** @var array<string, string[]> */
    protected array $messages = [];

    public function __construct(array $messages = [])
    {
        foreach ($messages as $key => $value) {
            $this->messages[$key] = is_array($value) ? $value : [$value];
        }
    }

    /** Devuelve true si el campo tiene errores */
    public function has(string $key): bool
    {
        return isset($this->messages[$key]) && count($this->messages[$key]) > 0;
    }

    /** Devuelve TODOS los mensajes del campo */
    public function get(string $key): array
    {
        return $this->messages[$key] ?? [];
    }

    /** Devuelve el primer mensaje del campo */
    public function first(string $key): ?string
    {
        return $this->has($key) ? $this->messages[$key][0] : null;
    }

    /** Devuelve todos los mensajes de todos los campos */
    public function all(): array
    {
        $all = [];
        foreach ($this->messages as $msgs) {
            foreach ($msgs as $msg) {
                $all[] = $msg;
            }
        }
        return $all;
    }

    /** Obtener array original por si quieres algo especial */
    public function toArray(): array
    {
        return $this->messages;
    }
}
