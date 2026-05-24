<?php

declare(strict_types=1);

namespace App\Core;

abstract class FormRequest extends Request
{
    protected array $validated = [];

    public abstract function rules(Validator $v): void;

    /** Ejecuta la validación y devuelve datos validados */
    public function validate(): array
    {
        $validator = new Validator($this);
        $this->rules($validator);
        return $this->validated = $validator->validate();
    }

    /** Devuelve los datos ya validados */
    public function validated(): array
    {
        return $this->validated;
    }
}