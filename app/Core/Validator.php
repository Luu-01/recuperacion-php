<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    protected Request $request;
    protected array $validatedFields = [];
    protected array $errors = [];

    protected string $currentFieldName = '';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validate(): array
    {
        if (!empty($this->errors)) {
            if ($this->request->expectsJson()) {
                json([
                    'message' => 'Datos no válidos',
                    'errors'  => $this->errors
                ], 422);
            }

            back()->withInput($this->request->input())->withErrors($this->errors)->send();
        }

        return array_intersect_key($this->request->input(), $this->validatedFields);
    }

    public function field(string $name): static
    {
        $this->currentFieldName = $name;
        $this->validatedFields[$name] = $this->request->input($name);
        return $this;
    }

    private function getCurrentFieldName(): string
    {
        $name = $this->currentFieldName;

        if ($name === '') {
            throw new \Exception('Debes establecer algún campo en el validador.');
        }

        return $name;
    }

    public function required(): static
    {
        $name  = $this->getCurrentFieldName();
        $value = $this->validatedFields[$name];

        if ($value === null || (is_string($value) && trim($value) === '')) {
            $this->errors[$name][] = "El campo $name es obligatorio.";
        }

        return $this;
    }

    public function string(): static
    {
        $name = $this->getCurrentFieldName();
        $value = $this->validatedFields[$name];

        if (!is_string($value)) {
            $this->errors[$name][] = "El campo $name debe ser una cadena de texto.";
        }

        return $this;
    }

    public function numeric(): static
    {
        $name = $this->getCurrentFieldName();
        $value = $this->validatedFields[$name];

        if (!is_numeric($value)) {
            $this->errors[$name][] = "El campo $name debe ser un valor numérico.";
        }

        return $this;
    }

    public function integer(): static
    {
        $name = $this->getCurrentFieldName();
        $value = $this->validatedFields[$name];

        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->errors[$name][] = "El campo $name debe ser un número entero.";
        }

        return $this;
    }

    public function min(int $min): static
    {
        $name  = $this->getCurrentFieldName();
        $value = $this->validatedFields[$name];

        if (is_numeric($value) && $value < $min) {
            $this->errors[$name][] = "El campo $name debe ser mayor o igual que $min.";
        }

        return $this;
    }

    public function minLength(int $min): static
    {
        $name  = $this->getCurrentFieldName();
        $value = $this->validatedFields[$name];

        if (is_string($value) && mb_strlen($value) < $min) {
            $this->errors[$name][] = "El campo $name debe tener al menos $min caracteres.";
        }
        return $this;
    }

    public function email(): static
    {
        $name  = $this->getCurrentFieldName();
        $value = $this->validatedFields[$name];

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$name][] = "El campo $name debe ser un correo electrónico válido.";
        }

        return $this;
    }
}
